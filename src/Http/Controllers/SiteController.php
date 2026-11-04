<?php
namespace ME\Nit\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ME\Nit\Services\DomainChecker;
use ME\Nit\Support\Content;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * Public Natore IT website. Content comes from the database (admin panel), see Support/Content.
 */
class SiteController
{
    public function home()
    {
        return view('nit::site.home', [
            'domains'   => Content::featuredDomains(),
            'packages'  => Content::webPackages(),
            'why'       => Content::list('why_choose'),
            'siteTypes' => Content::list('site_types'),
            'offers'    => Content::list('offer_tabs'),
            'hosting'   => Content::list('featured_hosting'),
        ]);
    }

    /** Hosting, VPS and SEO plan pages share one template. */
    public function plans(string $slug)
    {
        $page = Content::planPages()[$slug] ?? abort(404);

        return view('nit::site.plans', ['page' => $page, 'includes' => Content::list('plan_includes')]);
    }

    public function webDesign()
    {
        return view('nit::site.web-design', [
            'packages'  => Content::webPackages(),
            'why'       => Content::list('why_choose'),
            'siteTypes' => Content::list('site_types'),
        ]);
    }

    public function multiVendor()
    {
        return view('nit::site.multi-vendor', ['data' => Content::software('multi_vendor') ?? abort(404)]);
    }

    public function software(string $key)
    {
        $data = Content::software($key) ?? abort(404);
        $label = strtoupper($key);
        $titles = [$label . ' Software', $data['title'] ?? 'Online ' . $label . ' Software', 'Exciting Features of ' . $label];

        return view('nit::site.software', ['data' => $data, 'titles' => $titles]);
    }

    public function softwareSolution()
    {
        return view('nit::site.software-solution', ['items' => Content::list('software')]);
    }

    public function ssl()
    {
        return view('nit::site.ssl', ['plans' => Content::sslPlans()]);
    }

    public function domain()
    {
        return view('nit::site.domain', ['domains' => Content::featuredDomains(), 'links' => Content::list('domain_links')]);
    }

    public function about()
    {
        return view('nit::site.about', ['why' => Content::list('about_why'), 'team' => Content::team()]);
    }

    public function portfolio()
    {
        $data = Content::portfolio();
        // Hide filter buttons that would show no projects
        $used = array_unique(array_merge([], ...array_column($data['items'], 'categories')));
        $data['categories'] = array_intersect_key($data['categories'], array_flip($used));

        return view('nit::site.portfolio', $data);
    }

    public function reviews()
    {
        return view('nit::site.reviews', ['reviews' => Content::reviews()]);
    }

    public function contact()
    {
        return view('nit::site.contact');
    }

    public function coupon()
    {
        return view('nit::site.coupon');
    }

    /**
     * Live domain availability (JSON) for the website's domain search.
     */
    public function domainCheck(Request $request, DomainChecker $checker): JsonResponse
    {
        // Accept "name", "name.com", "www.name.com" or a full URL
        $input = strtolower(trim((string) $request->query('domain')));
        $input = preg_replace(['~^[a-z]+://~', '~^www\.~', '~[/?#].*$~'], '', $input);
        [$sld, $tld] = array_pad(explode('.', $input, 2), 2, '');
        $tld = $tld === '' ? '.com' : '.' . $tld;

        if (! preg_match('/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/', $sld) || ! preg_match('/^(\.[a-z0-9-]{2,24}){1,2}$/', $tld)) {
            return response()->json(['message' => 'Enter a domain name using letters, numbers and hyphens, e.g. natoreshop.com.'], 422);
        }

        try {
            $result = $checker->check($sld, $tld);
        } catch (Throwable $e) {
            Log::warning('Domain check failed: ' . $e->getMessage(), ['domain' => $sld . $tld]);

            return response()->json([
                'message'  => 'We could not check availability right now. Please try again, or search on our billing site.',
                'cart_url' => $checker->cartUrl($sld . $tld),
            ], 503);
        }

        // Where each result's action button leads: WHMCS cart, or our contact form for TLDs not sold online
        $withCart = fn ($item) => $item + ['unsupported' => false, 'cart_url' => match (true) {
            ! empty($item['unsupported']) => route('nit.site.contact', ['subject' => 'hosting', 'domain' => $item['domain']]),
            $item['message'] !== null     => null,
            default                       => $checker->cartUrl($item['domain'], $item['available'] ? 'register' : 'transfer'),
        }];

        return response()->json([
            'query'       => $result['query'],
            'primary'     => $result['primary'] ? $withCart($result['primary']) : null,
            'spotlight'   => array_map($withCart, $result['spotlight']),
            'suggestions' => array_map($withCart, array_slice($result['suggestions'], 0, 8)),
        ]);
    }

    /**
     * Serves the package's CSS/JS so the site works without vendor:publish.
     * Published copies in public/vendor/nit are served by the web server first.
     */
    public function asset(string $path): BinaryFileResponse
    {
        $base = realpath(__DIR__ . '/../../public/vendor/nit');
        $file = realpath($base . '/' . $path);

        abort_unless($file && str_starts_with($file, $base . DIRECTORY_SEPARATOR) && is_file($file), 404);

        $types = ['css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png', 'svg' => 'image/svg+xml', 'webp' => 'image/webp'];
        $type  = $types[pathinfo($file, PATHINFO_EXTENSION)] ?? abort(404);

        return response()->file($file, [
            'Content-Type'  => str_starts_with($type, 'image/') && $type !== 'image/svg+xml' ? $type : $type . '; charset=utf-8',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
