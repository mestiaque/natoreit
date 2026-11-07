<?php
namespace ME\Nit\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use ME\Nit\Models\DomainPrice;
use ME\Nit\Models\ListItem;
use ME\Nit\Models\PlanPage;
use ME\Nit\Models\PortfolioCategory;
use ME\Nit\Models\PortfolioItem;
use ME\Nit\Models\Review;
use ME\Nit\Models\Setting;
use ME\Nit\Models\SoftwarePage;
use ME\Nit\Models\SslPlan;
use ME\Nit\Models\TeamMember;
use ME\Nit\Models\WebPackage;
use Throwable;

/**
 * Website content from the database (edited in the admin panel), cached until
 * something changes. Shapes match the static defaults in Catalog / SiteData,
 * which are used when the tables are missing (e.g. before migrating).
 */
class Content
{
    private const CACHE_KEY = 'nit.site.content.';

    /** Every cached key, so a change can clear them all (works on any cache store). */
    private const KEYS = ['settings', 'plan_pages', 'web_packages', 'ssl', 'domains', 'portfolio', 'team', 'reviews', 'software', 'lists'];

    public static function flush(): void
    {
        foreach (self::KEYS as $key) {
            Cache::forget(self::CACHE_KEY . $key);
        }
    }

    private static function remember(string $key, callable $fromDatabase, callable $fallback): mixed
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY . $key, $fromDatabase);
        } catch (Throwable $e) {
            Log::warning('Website content falls back to defaults: ' . $e->getMessage());

            return $fallback();
        }
    }

    /**
     * Texts of the fixed pages: browser title, search description, small label,
     * heading and introduction. Saved texts (admin › Page texts) override these.
     */
    public const PAGES = [
        'home' => ['name' => 'Home', 'meta_title' => '', 'description' => 'Natore IT is a domain reseller and hosting provider in Bangladesh that also builds websites, ERP, POS and custom web applications.',
            'eyebrow' => 'Domain · Hosting · Website · ERP · POS · Custom Software', 'title' => 'Technology that moves', 'highlight' => 'your business forward',
            'lead' => 'Register your domain, host it on fast servers, and let our team build the website, ERP, POS or custom web application your business needs.'],
        'web_design' => ['name' => 'Web Design', 'meta_title' => 'Web Design', 'description' => 'Web design packages from Natore-IT: domain, hosting, responsive design, on-page SEO and lifetime support from $62.',
            'eyebrow' => 'Web Design', 'title' => 'Web Design Package for you',
            'lead' => 'Natore-IT provides a hassle-free web design service — a complete website with domain, hosting and business email in one price.'],
        'multi_vendor' => ['name' => 'Multi Vendor Ecommerce', 'meta_title' => 'Multi Vendor Ecommerce', 'description' => 'Multi vendor ecommerce development by Natore-IT: admin, vendor and customer panels, payment and SMS gateways, Android & iOS apps.',
            'eyebrow' => 'E-commerce', 'title' => 'Multi Vendor Ecommerce', 'lead' => ''],
        'software_solution' => ['name' => 'Software Solution', 'meta_title' => 'Software Development', 'description' => 'Software development in Bangladesh by Natore-IT: ERP, POS, custom web applications, and hospital, school, inventory and accounting software.',
            'eyebrow' => 'Software', 'title' => 'Software Development in Bangladesh',
            'lead' => 'We build ERP, POS and custom web applications that fit how your business works, so you can manage inventory, accounts, customers and staff from one system.'],
        'ssl' => ['name' => 'SSL', 'meta_title' => 'SSL Certificates', 'description' => 'Comodo and RapidSSL certificates from Natore-IT — PositiveSSL from $8.50 per year.',
            'eyebrow' => 'Security', 'title' => 'SSL Plan Perfect For You!', 'lead' => 'Secure your website with a trusted SSL certificate — HTTPS, the padlock in the browser and better search rankings.'],
        'domain' => ['name' => 'Domain', 'meta_title' => 'Domain Registration', 'description' => 'Register .com, .net, .info and .edu.bd domains with Natore-IT, or become a domain reseller.',
            'eyebrow' => 'Domain', 'title' => 'Find your perfect', 'highlight' => 'domain name',
            'lead' => 'Choose your domain name as per your business requirements and take your business online.'],
        'about' => ['name' => 'About', 'meta_title' => 'About Us', 'description' => 'Natore-IT is a domain reseller and hosting provider that also builds websites, ERP, POS and custom web applications.',
            'eyebrow' => 'About Us', 'title' => 'Your domain, hosting and software in one place', 'lead' => 'One team for your domain, hosting, website, ERP, POS and custom web applications, with 24/7 support.'],
        'portfolio' => ['name' => 'Portfolio', 'meta_title' => 'Portfolio', 'description' => 'Websites and software built by Natore-IT for businesses, e-commerce, schools, newspapers and buying houses.',
            'eyebrow' => 'Portfolio', 'title' => 'Portfolio Natore-IT', 'lead' => ':count websites and applications we have designed and built for our clients.'],
        'reviews' => ['name' => 'Review', 'meta_title' => 'Customer Review', 'description' => 'What customers say about Natore-IT hosting, domains, web design and support.',
            'eyebrow' => 'Review', 'title' => 'Customer Review', 'lead' => 'What our clients say about our hosting, domain, web design and support.'],
        'contact' => ['name' => 'Contact', 'meta_title' => 'Contact Us', 'description' => 'Contact Natore-IT: support team, sales and our Dhaka office in Uttara. 24/7 support.',
            'eyebrow' => 'Contact Us', 'title' => 'Contact Us', 'lead' => 'Feel free to talk to our team at any time — by phone, WhatsApp or the form below. 24/7 support!'],
        'quote' => ['name' => 'Get a quote', 'meta_title' => 'Get a Quote', 'description' => 'Tell Natore-IT about your project and get a quote or software demo.',
            'eyebrow' => 'Get a quote', 'title' => 'Tell us about your project', 'lead' => 'Share a few details and our team will contact you with a demo or a quote.'],
        'not_found' => ['name' => 'Page not found', 'meta_title' => 'Page not found', 'description' => '',
            'eyebrow' => '404', 'title' => 'This page could not be found', 'lead' => 'The link may be old or mistyped. These pages can help you continue.'],
        'coupon' => ['name' => 'Coupon', 'meta_title' => 'Coupon', 'description' => '',
            'eyebrow' => 'Offer', 'title' => 'Coupon', 'lead' => 'Save on your first hosting order with our current coupon.'],
    ];

    /** Texts of one fixed page (see PAGES); an empty saved text keeps the default. */
    public static function page(string $key): array
    {
        $saved = array_filter((array) (self::settings()['pages'][$key] ?? []), fn ($v) => $v !== null && $v !== '');

        return array_replace(self::PAGES[$key] ?? [], $saved);
    }

    /** Contact details and links: config('nit.site') overridden by the saved settings. */
    public static function settings(): array
    {
        $saved = self::remember('settings', fn () => Setting::pluck('value', 'key')->all(), fn () => []);

        return array_replace_recursive(config('nit.site', []), $saved);
    }

    /** A fixed text of the website, or its replacement from admin › Appearance. */
    public static function text(string $default): string
    {
        $saved = self::settings()['texts'][md5($default)] ?? null;

        return filled($saved) ? $saved : $default;
    }

    /** A fixed icon of the website, or its replacement from admin › Appearance. */
    public static function icon(string $default): string
    {
        return self::settings()['icons'][$default] ?? $default;
    }

    /** Plan pages by slug: title, heading, eyebrow, icon, lead, includes_title, about[], plans[], rows[]. */
    public static function planPages(): array
    {
        return self::remember('plan_pages', function () {
            return PlanPage::with('features', 'plans')->active()->ordered()->get()->mapWithKeys(fn (PlanPage $page) => [$page->slug => [
                'title'          => $page->title,
                'heading'        => $page->heading,
                'eyebrow'        => $page->eyebrow,
                'menu_text'      => $page->menu_text,
                'icon'           => $page->icon ?: 'bi-hdd-stack',
                'lead'           => (string) $page->lead,
                'includes_title' => $page->includes_title,
                'about'          => self::paragraphs($page->about),
                'plans'          => $page->plans->map(fn ($plan) => [
                    'name' => $plan->name, 'price' => $plan->price, 'regular' => $plan->regular_price, 'period' => $plan->period,
                    'order' => $plan->order_url ?: 'https://billing.natoreit.com/cart.php', 'featured' => $plan->is_featured,
                ])->all(),
                'rows' => $page->features->map(fn ($feature) => [
                    $feature->label, ...$page->plans->map(fn ($plan) => $plan->valueFor($feature->id))->all(),
                ])->all(),
            ]])->all();
        }, fn () => Catalog::planPages());
    }

    public static function webPackages(): array
    {
        return self::remember('web_packages', fn () => WebPackage::active()->ordered()->get()->map(fn ($p) => [
            'name' => $p->name, 'order' => $p->order_url ?: 'https://billing.natoreit.com/cart.php', 'icon' => $p->icon ?: 'bi-window',
            'price' => $p->price, 'regular' => $p->regular_price ?: $p->price, 'renewal' => $p->renewal_price ?: 0,
            'featured' => $p->is_featured, 'features' => $p->featureList(),
        ])->all(), fn () => SiteData::webPackages());
    }

    /** [name, price, order url, note] */
    public static function sslPlans(): array
    {
        return self::remember('ssl', fn () => SslPlan::active()->ordered()->get()
            ->map(fn ($p) => [$p->name, $p->price, $p->order_url ?: 'https://billing.natoreit.com/cart.php', (string) $p->note])->all(),
            fn () => array_map(fn ($p) => [$p[0], $p[1], Catalog::sslOrderUrl($p[2]), $p[3]], Catalog::sslPlans()));
    }

    public static function featuredDomains(): array
    {
        return self::remember('domains', fn () => DomainPrice::active()->ordered()->get()
            ->map(fn ($d) => ['tld' => $d->tld, 'price' => $d->price, 'period' => $d->period])->all(),
            fn () => SiteData::featuredDomains());
    }

    /** ['categories' => [key => label], 'items' => [title, url, image, categories[]]] */
    public static function portfolio(): array
    {
        return self::remember('portfolio', function () {
            $categories = PortfolioCategory::ordered()->get()->mapWithKeys(fn ($c) => ['c' . $c->id => $c->name])->all();

            return [
                'categories' => $categories,
                'items'      => PortfolioItem::active()->ordered()->get()->map(fn ($item) => [
                    'title' => $item->title, 'url' => $item->url ?: '#', 'image' => self::mediaUrl($item->image),
                    'categories' => array_values(array_intersect(array_map(fn ($id) => 'c' . $id, $item->categories ?? []), array_keys($categories))),
                ])->all(),
            ];
        }, fn () => Catalog::content('portfolio'));
    }

    public static function team(): array
    {
        return self::remember('team', fn () => TeamMember::active()->ordered()->get()->map(fn ($m) => [
            'name' => $m->name, 'role' => (string) $m->role, 'bio' => (string) $m->bio, 'image' => self::mediaUrl($m->image),
        ])->all(), fn () => Catalog::content('team'));
    }

    public static function reviews(): array
    {
        return self::remember('reviews', fn () => Review::active()->ordered()->get()->map(fn ($r) => [
            'title' => (string) $r->title, 'date' => (string) $r->date_label, 'rating' => $r->rating, 'text' => $r->text, 'author' => $r->author,
        ])->all(), fn () => Catalog::content('reviews'));
    }

    /**
     * pos / crm / erp / custom: ['title', 'intro' => string, 'sections' => [title, items[]]];
     * multi_vendor: ['title', 'intro' => paragraphs[], 'groups' => [title, items[], heading?]].
     */
    public static function software(string $key): ?array
    {
        $pages = self::remember('software', fn () => SoftwarePage::all()->keyBy('key')->map(fn ($p) => [
            'title' => $p->title, 'intro' => (string) $p->intro, 'sections' => $p->sections ?? [],
        ])->all(), fn () => []);

        if (! isset($pages[$key])) {
            return Catalog::content('software')[$key] ?? null;
        }

        $page = $pages[$key];

        return $key === 'multi_vendor'
            ? ['title' => $page['title'], 'intro' => self::paragraphs($page['intro']), 'groups' => $page['sections']]
            : $page;
    }

    /**
     * A small icon list (see ListItem::LISTS):
     * why_choose [icon, title, text]; featured_hosting [icon, title, text, bullets[]];
     * domain_links [title, url, icon]; payment_methods [title]; stats [icon, number, label]; software_home [icon, title, text, url];
     * faqs [question, answer]; others [icon, title].
     */
    public static function list(string $name): array
    {
        $lists = self::remember('lists', fn () => ListItem::active()->ordered()->get(['list', 'icon', 'title', 'text', 'url'])
            ->groupBy('list')->map(fn ($items) => $items->map->only(['icon', 'title', 'text', 'url'])->all())->all(), fn () => null);

        if ($lists === null || ! isset($lists[$name])) {
            return self::defaultList($name);
        }

        return array_map(fn (array $item) => match ($name) {
            'why_choose'       => [$item['icon'], $item['title'], (string) $item['text']],
            'featured_hosting' => [$item['icon'], $item['title'], ...self::firstLineAndRest($item['text']), $item['url'] ?: '#'],
            'offer_tabs'       => [$item['icon'], $item['title'], ...self::firstLineAndRest($item['text']), $item['url'] ?: '#'],
            'footer_resources' => [$item['title'], $item['url'] ?: '#'],
            'domain_links'     => [$item['title'], $item['url'] ?: '#', $item['icon']],
            'payment_methods'  => $item['title'],
            'stats'            => [$item['icon'], $item['title'], (string) $item['text']],
            'process'          => [$item['icon'], $item['title'], (string) $item['text']],
            'tech'             => $item['title'],
            'quote_services'   => $item['title'],
            'quote_budgets'    => $item['title'],
            'software_home'    => [$item['icon'], $item['title'], (string) $item['text'], $item['url'] ?: '#'],
            'faqs'             => [$item['title'], (string) $item['text']],
            default            => [$item['icon'], $item['title']],
        }, $lists[$name]);
    }

    private static function defaultList(string $name): array
    {
        return match ($name) {
            'why_choose'       => SiteData::whyChooseUs(),
            'featured_hosting' => array_map(fn ($r, $slug) => [...$r, url($slug)], SiteData::featuredHosting(), ['shared-hosting', 'reseller-hosting', 'master-reseller-hosting']),
            'offer_tabs'       => array_map(fn ($t) => [$t[0], $t[1], $t[2], $t[4], url($t[3])], SiteData::offerTabs()),
            'footer_resources' => self::defaultResources(),
            'site_types'       => SiteData::siteTypes(),
            'plan_includes'    => Catalog::includes(),
            'about_why'        => Catalog::aboutWhy(),
            'software'         => Catalog::softwareSolutions(),
            'domain_links'     => Catalog::domainLinks(),
            'payment_methods'  => SiteData::paymentMethods(),
            'stats'            => SiteData::stats(),
            'process'          => SiteData::process(),
            'tech'             => SiteData::tech(),
            'quote_services'   => SiteData::quoteServices(),
            'quote_budgets'    => SiteData::quoteBudgets(),
            'software_home'    => array_map(fn ($r) => [$r[0], $r[1], $r[2], url($r[3])], SiteData::softwareHome()),
            'faqs'             => SiteData::faqs(),
            default            => [],
        };
    }

    private static function defaultResources(): array
    {
        $links = config('nit.site.links');

        return [['Outlook configuration step by step', $links['knowledgebase'] ?? '#'], ['3 Reasons Websites Are Vital for Small Businesses', $links['news'] ?? '#']];
    }

    /** "Intro line\nbullet\nbullet" => ["Intro line", ["bullet", "bullet"]] */
    private static function firstLineAndRest(?string $text): array
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $text))));

        return [array_shift($lines) ?? '', $lines];
    }

    /** Paragraphs separated by blank lines. */
    public static function paragraphs(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', (string) $text))));
    }

    /** Uploaded files are stored on the admin panel's disk; full URLs are used as they are. */
    public static function mediaUrl(?string $path): string
    {
        if (! $path) {
            return '';
        }

        return preg_match('~^(https?:)?//~', $path) ? $path : \Illuminate\Support\Facades\Storage::disk(config('nit.disk', 'public'))->url($path);
    }

    /**
     * Sections typed in the admin panel:
     *   # Block heading   (multi-vendor only)
     *   ## Section title
     *   - item
     */
    public static function parseSections(string $text): array
    {
        $sections = [];
        foreach (preg_split('/\R/', $text) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            if (str_starts_with($line, '## ')) {
                $sections[] = ['title' => trim(substr($line, 3)), 'items' => []];
            } elseif (str_starts_with($line, '# ')) {
                $sections[] = ['title' => trim(substr($line, 2)), 'items' => [], 'heading' => true];
            } else {
                if (! $sections) {
                    $sections[] = ['title' => 'Features', 'items' => []];
                }
                $sections[array_key_last($sections)]['items'][] = ltrim($line, "-•* \t");
            }
        }

        return $sections;
    }

    public static function sectionsToText(array $sections): string
    {
        $out = [];
        foreach ($sections as $section) {
            $out[] = (! empty($section['heading']) ? '# ' : '## ') . $section['title'];
            foreach ($section['items'] ?? [] as $item) {
                $out[] = '- ' . $item;
            }
            $out[] = '';
        }

        return rtrim(implode("\n", $out));
    }
}
