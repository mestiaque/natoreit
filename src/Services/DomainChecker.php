<?php
namespace ME\Nit\Services;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Domain availability lookup backed by the WHMCS billing system.
 *
 * Drivers (config nit.domain_checker.driver):
 *  - whmcs-cart: the same /domain/check endpoint WHMCS's own cart uses (no credentials needed)
 *  - whmcs-api:  the official WHMCS API (DomainWhois + GetTLDPricing); needs API credentials
 *  - demo:       deterministic sample data, for local development and tests
 *
 * Every driver returns the same normalised shape:
 *  ['query' => 'name.com', 'primary' => item|null, 'spotlight' => item[], 'suggestions' => item[]]
 *  item = ['domain', 'available', 'premium', 'price', 'renew', 'period', 'message', 'unsupported'?]
 */
class DomainChecker
{
    private array $config;

    public function __construct()
    {
        $this->config = config('nit.domain_checker');
    }

    public function check(string $sld, string $tld): array
    {
        $key = 'nit.domain-check.' . $this->config['driver'] . '.' . $sld . $tld;

        return Cache::remember($key, $this->config['cache_ttl'], fn () => match ($this->config['driver']) {
            'whmcs-api' => $this->viaApi($sld, $tld),
            'demo'      => $this->viaDemo($sld, $tld),
            default     => $this->viaCart($sld, $tld),
        });
    }

    public function cartUrl(string $domain, string $action = 'register'): string
    {
        return $this->billingUrl('/cart.php') . '?' . http_build_query(['a' => 'add', 'domain' => $action, 'query' => $domain]);
    }

    /* whmcs-cart ------------------------------------------------------------ */

    private function viaCart(string $sld, string $tld): array
    {
        try {
            $responses = $this->cartRequests($sld, $tld, $this->cartSession());
        } catch (RuntimeException) {
            // Session or token expired: start a fresh one and retry once
            $responses = $this->cartRequests($sld, $tld, $this->cartSession(true));
        }

        $map = fn ($items) => array_values(array_map(fn ($i) => $this->fromCart($i), (array) $items));
        $primary = $map($responses['domain'])[0] ?? null;

        // WHMCS silently falls back to another extension when the TLD is not sold online
        if ($primary === null || $primary['domain'] !== $sld . $tld) {
            $primary = $this->unsupported($sld . $tld, $tld);
        }

        return [
            'query'       => $sld . $tld,
            'primary'     => $primary,
            'spotlight'   => array_values(array_filter($map($responses['spotlight']), fn ($i) => $i['domain'] !== ($primary['domain'] ?? null))),
            'suggestions' => $map($responses['suggestions']),
        ];
    }

    private function cartRequests(string $sld, string $tld, array $session): array
    {
        $jar = new CookieJar(false, $session['cookies']);
        $url = $this->billingUrl('/index.php?rp=/domain/check');
        $types = ['domain', 'spotlight', 'suggestions'];

        $responses = Http::pool(fn (Pool $pool) => array_map(
            fn ($type) => $pool->as($type)
                ->withOptions(['cookies' => $jar, 'allow_redirects' => false])
                ->timeout($this->config['timeout'])
                ->asForm()
                ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                ->post($url, [
                    'token' => $session['token'], 'type' => $type,
                    'domain' => $sld . $tld, 'sld' => $sld, 'tld' => $tld, 'source' => 'cartAddDomain',
                ]),
            $types
        ));

        $results = [];
        foreach ($types as $type) {
            $response = $responses[$type];
            if (! $response instanceof \Illuminate\Http\Client\Response || ! $response->successful() || ! is_array($response->json('result'))) {
                throw new RuntimeException("WHMCS domain check ({$type}) failed");
            }
            $results[$type] = $response->json('result');
        }

        return $results;
    }

    /** WHMCS session cookies + CSRF token, cached so most searches need no extra request. */
    private function cartSession(bool $fresh = false): array
    {
        $key = 'nit.domain-check.whmcs-session';
        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, 900, function () {
            $jar = new CookieJar();
            $html = Http::withOptions(['cookies' => $jar])
                ->timeout($this->config['timeout'])
                ->get($this->billingUrl('/cart.php'), ['a' => 'add', 'domain' => 'register'])
                ->throw()
                ->body();

            if (! preg_match("/csrfToken\s*=\s*'([a-f0-9]+)'/", $html, $m)) {
                throw new RuntimeException('WHMCS CSRF token not found');
            }

            return ['token' => $m[1], 'cookies' => $jar->toArray()];
        });
    }

    private function fromCart(array $item): array
    {
        $period = $item['shortestPeriod'] ?? [];

        return [
            'domain'    => $item['domainName'] ?? '',
            'available' => (bool) ($item['isAvailable'] ?? false),
            'premium'   => (bool) ($item['isPremium'] ?? false),
            'price'     => $this->cleanPrice($period['register'] ?? null),
            'renew'     => $this->cleanPrice($period['renew'] ?? null),
            'period'    => (int) ($period['period'] ?? 1),
            'message'   => ($item['isValidDomain'] ?? true) ? null : ($item['domainErrorMessage'] ?: 'This domain name is not valid.'),
        ];
    }

    private function unsupported(string $domain, string $tld): array
    {
        return [
            'domain' => $domain, 'available' => false, 'premium' => false, 'price' => null, 'renew' => null, 'period' => 1,
            'unsupported' => true,
            'message' => "{$tld} domains can't be registered online yet. Contact us and we will register it for you.",
        ];
    }

    /* whmcs-api -------------------------------------------------------------- */

    private function viaApi(string $sld, string $tld): array
    {
        $pricing = $this->apiPricing();
        $tlds = array_unique(array_merge([$tld], $this->config['spotlight_tlds']));

        $responses = Http::pool(fn (Pool $pool) => array_map(
            fn ($t) => $pool->as($t)->timeout($this->config['timeout'])->asForm()
                ->post($this->billingUrl('/includes/api.php'), $this->apiParams('DomainWhois', ['domain' => $sld . $t])),
            $tlds
        ));

        $items = [];
        foreach ($tlds as $t) {
            $response = $responses[$t];
            if (! $response instanceof \Illuminate\Http\Client\Response || $response->json('result') !== 'success') {
                continue;
            }
            $price = $pricing['tlds'][ltrim($t, '.')] ?? null;
            $items[$t] = [
                'domain'    => $sld . $t,
                'available' => $response->json('status') === 'available',
                'premium'   => false,
                'price'     => $this->formatApiPrice($price['register']['1'] ?? null, $pricing['currency']),
                'renew'     => $this->formatApiPrice($price['renew']['1'] ?? null, $pricing['currency']),
                'period'    => 1,
                'message'   => null,
            ];
        }

        if (! isset($items[$tld])) {
            throw new RuntimeException('WHMCS API lookup failed');
        }
        if (! isset($pricing['tlds'][ltrim($tld, '.')])) {
            $items[$tld] = $this->unsupported($sld . $tld, $tld);
        }

        return [
            'query'       => $sld . $tld,
            'primary'     => $items[$tld],
            'spotlight'   => array_values(array_diff_key($items, [$tld => true])),
            'suggestions' => [],
        ];
    }

    private function apiPricing(): array
    {
        return Cache::remember('nit.domain-check.whmcs-pricing', 3600, function () {
            $response = Http::timeout($this->config['timeout'])->asForm()
                ->post($this->billingUrl('/includes/api.php'), $this->apiParams('GetTLDPricing', ['currencyid' => $this->config['api']['currency_id']]));

            if ($response->json('result') !== 'success') {
                throw new RuntimeException('WHMCS GetTLDPricing failed: ' . $response->json('message'));
            }

            return ['tlds' => $response->json('pricing', []), 'currency' => $response->json('currency', [])];
        });
    }

    private function apiParams(string $action, array $params): array
    {
        return $params + [
            'action'       => $action,
            'identifier'   => $this->config['api']['identifier'],
            'secret'       => $this->config['api']['secret'],
            'responsetype' => 'json',
        ];
    }

    private function formatApiPrice(?string $amount, array $currency): ?string
    {
        return $amount === null || (float) $amount < 0 ? null : ($currency['prefix'] ?? '') . $amount;
    }

    /* demo ------------------------------------------------------------------- */

    private function viaDemo(string $sld, string $tld): array
    {
        $item = function (string $domain) {
            $available = crc32($domain) % 3 !== 0;

            return ['domain' => $domain, 'available' => $available, 'premium' => false, 'price' => '$11.66', 'renew' => '$11.66', 'period' => 1, 'message' => null];
        };
        $spotlight = array_diff($this->config['spotlight_tlds'], [$tld]);

        return [
            'query'       => $sld . $tld,
            'primary'     => $item($sld . $tld),
            'spotlight'   => array_values(array_map(fn ($t) => $item($sld . $t), $spotlight)),
            'suggestions' => [$item($sld . 'bd.com'), $item('my' . $sld . '.com'), $item($sld . 'online.net')],
        ];
    }

    /* helpers ---------------------------------------------------------------- */

    private function billingUrl(string $path): string
    {
        return rtrim($this->config['billing_url'], '/') . $path;
    }

    /** "$11.66 USD" → "$11.66" */
    private function cleanPrice(?string $price): ?string
    {
        return $price === null || $price === '' ? null : trim(preg_replace('/\s+[A-Z]{3}$/', '', $price));
    }
}
