<?php
namespace ME\Nit\Support;

/**
 * Where each part of the admin panel shows on the website, so the menu and
 * every admin page can say "Shown on: Home, Web Design".
 */
class AdminMap
{
    /** Website pages: key => [label, route name, route parameter]. */
    public const PAGES = [
        'home'          => ['Home', 'nit.site.home'],
        'hosting'       => ['Hosting pages', 'nit.site.plans', 'shared-hosting'],
        'vps'           => ['VPS', 'nit.site.plans', 'vps'],
        'seo'           => ['SEO', 'nit.site.plans', 'seo'],
        'web_design'    => ['Web Design', 'nit.site.web-design'],
        'multi_vendor'  => ['Multi Vendor', 'nit.site.multi-vendor'],
        'pos'           => ['POS Software', 'nit.site.pos'],
        'crm'           => ['CRM Software', 'nit.site.crm'],
        'software'      => ['Software Solution', 'nit.site.software-solution'],
        'ssl'           => ['SSL', 'nit.site.ssl'],
        'domain'        => ['Domain', 'nit.site.domain'],
        'about'         => ['About', 'nit.site.about'],
        'portfolio'     => ['Portfolio', 'nit.site.portfolio'],
        'reviews'       => ['Review', 'nit.site.reviews'],
        'contact'       => ['Contact', 'nit.site.contact'],
        'coupon'        => ['Coupon', 'nit.site.coupon'],
        'all'           => ['Every page (header / footer)', 'nit.site.home'],
    ];

    /** Admin area => website pages it shows on. */
    public const SHOWN_ON = [
        'packages'             => ['home', 'web_design'],
        'domains'              => ['home', 'domain'],
        'ssl'                  => ['ssl'],
        'plan-pages'           => ['hosting', 'vps', 'seo', 'all'],
        'plans'                => ['hosting', 'vps', 'seo'],
        'software'             => ['pos', 'crm', 'multi_vendor'],
        'portfolio'            => ['portfolio'],
        'portfolio-categories' => ['portfolio'],
        'team'                 => ['about'],
        'reviews'              => ['reviews'],
        'settings'             => ['all', 'contact', 'coupon'],
        'list:why_choose'      => ['home', 'web_design'],
        'list:site_types'      => ['home', 'web_design'],
        'list:featured_hosting'=> ['home'],
        'list:offer_tabs'      => ['home'],
        'list:plan_includes'   => ['hosting', 'vps'],
        'list:about_why'       => ['about'],
        'list:software'        => ['software'],
        'list:domain_links'    => ['domain'],
        'list:payment_methods' => ['all'],
        'list:footer_resources'=> ['all'],
    ];

    /** Page-text tab => website page. */
    public const TEXTS = [
        'home' => 'home', 'web_design' => 'web_design', 'multi_vendor' => 'multi_vendor', 'software_solution' => 'software', 'ssl' => 'ssl',
        'domain' => 'domain', 'about' => 'about', 'portfolio' => 'portfolio', 'reviews' => 'reviews', 'contact' => 'contact', 'coupon' => 'coupon',
    ];

    /** [label => url] of the pages an admin area shows on. */
    public static function links(string $area): array
    {
        $links = [];
        foreach (self::SHOWN_ON[$area] ?? [] as $page) {
            [$label, $route] = self::PAGES[$page];
            $links[$label] = route($route, self::PAGES[$page][2] ?? []);
        }

        return $links;
    }

    public static function pageUrl(string $page): string
    {
        [, $route] = self::PAGES[$page];

        return route($route, self::PAGES[$page][2] ?? []);
    }
}
