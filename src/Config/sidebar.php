<?php
// Admin menu of the website (nit-master sidebar: config('sidebar.nit')), one group per website page.
// Items that appear on several pages (e.g. web design packages) are listed under each of them.
$texts = fn (string $page) => ['title' => 'Page texts', 'icon' => 'fas fa-heading', 'url' => '/admin/page-texts/' . $page, 'permission' => 'site_page_text_edit'];
$list = fn (string $list, string $title, string $icon) => ['title' => $title, 'icon' => $icon, 'url' => '/admin/lists/' . $list, 'permission' => 'site_list_view'];
$packages = ['title' => 'Web design packages', 'icon' => 'fas fa-palette', 'route' => 'nit.admin.packages.index', 'permission' => 'site_package_view'];

return [
    ['sl' => 1, 'title' => 'Dashboard', 'icon' => 'fas fa-gauge', 'route' => 'nit.admin.dashboard'],
    ['sl' => 2, 'header' => 'Website pages'],
    ['sl' => 3, 'title' => 'Home', 'icon' => 'fas fa-house', 'children' => [
        $texts('home'),
        ['title' => 'Domain prices', 'icon' => 'fas fa-globe', 'route' => 'nit.admin.domains.index', 'permission' => 'site_domain_view'],
        $packages,
        $list('why_choose', 'Why choose us', 'fas fa-thumbs-up'),
        $list('site_types', 'Website types', 'fas fa-shapes'),
        $list('offer_tabs', 'What more we offer', 'fas fa-table-columns'),
        $list('featured_hosting', 'Hosting cards', 'fas fa-server'),
    ]],
    ['sl' => 4, 'title' => 'Hosting · VPS · SEO', 'icon' => 'fas fa-server', 'children' => [
        ['title' => 'Pages, plans & prices', 'icon' => 'fas fa-layer-group', 'route' => 'nit.admin.plan-pages.index', 'permission' => 'site_plan_page_view'],
        $list('plan_includes', 'All plans include', 'fas fa-list-check'),
    ]],
    ['sl' => 5, 'title' => 'Web Design', 'icon' => 'fas fa-palette', 'children' => [
        $texts('web_design'),
        $packages,
        $list('why_choose', 'Why choose us', 'fas fa-thumbs-up'),
        $list('site_types', 'Website types', 'fas fa-shapes'),
    ]],
    ['sl' => 6, 'title' => 'Software', 'icon' => 'fas fa-cubes', 'children' => [
        ['title' => 'POS, CRM & Multi Vendor', 'icon' => 'fas fa-cash-register', 'route' => 'nit.admin.software.index', 'permission' => 'site_software_view'],
        $texts('multi_vendor'),
        ['title' => 'Software Solution texts', 'icon' => 'fas fa-heading', 'url' => '/admin/page-texts/software_solution', 'permission' => 'site_page_text_edit'],
        $list('software', 'Software Solution list', 'fas fa-code'),
    ]],
    ['sl' => 7, 'title' => 'SSL', 'icon' => 'fas fa-lock', 'children' => [
        $texts('ssl'),
        ['title' => 'SSL plans', 'icon' => 'fas fa-lock', 'route' => 'nit.admin.ssl.index', 'permission' => 'site_ssl_view'],
    ]],
    ['sl' => 8, 'title' => 'Domain', 'icon' => 'fas fa-globe', 'children' => [
        $texts('domain'),
        ['title' => 'Domain prices', 'icon' => 'fas fa-tags', 'route' => 'nit.admin.domains.index', 'permission' => 'site_domain_view'],
        $list('domain_links', 'Sign-up & login links', 'fas fa-link'),
    ]],
    ['sl' => 9, 'title' => 'About', 'icon' => 'fas fa-circle-info', 'children' => [
        $texts('about'),
        $list('about_why', 'Why choose us', 'fas fa-thumbs-up'),
        ['title' => 'Team', 'icon' => 'fas fa-users', 'route' => 'nit.admin.team.index', 'permission' => 'site_team_view'],
    ]],
    ['sl' => 10, 'title' => 'Portfolio', 'icon' => 'fas fa-briefcase', 'children' => [
        $texts('portfolio'),
        ['title' => 'Projects', 'icon' => 'fas fa-briefcase', 'route' => 'nit.admin.portfolio.index', 'permission' => 'site_portfolio_view'],
        ['title' => 'Categories', 'icon' => 'fas fa-tags', 'route' => 'nit.admin.portfolio-categories.index', 'permission' => 'site_portfolio_category_view'],
    ]],
    ['sl' => 11, 'title' => 'Review', 'icon' => 'fas fa-star', 'children' => [
        $texts('reviews'),
        ['title' => 'Customer reviews', 'icon' => 'fas fa-star', 'route' => 'nit.admin.reviews.index', 'permission' => 'site_review_view'],
    ]],
    ['sl' => 12, 'title' => 'Contact & Coupon', 'icon' => 'fas fa-address-book', 'children' => [
        ['title' => 'Contact page texts', 'icon' => 'fas fa-heading', 'url' => '/admin/page-texts/contact', 'permission' => 'site_page_text_edit'],
        ['title' => 'Coupon page texts', 'icon' => 'fas fa-heading', 'url' => '/admin/page-texts/coupon', 'permission' => 'site_page_text_edit'],
        ['title' => 'Phones, address & coupon', 'icon' => 'fas fa-sliders', 'route' => 'nit.admin.settings', 'permission' => 'site_settings_edit'],
    ]],
    ['sl' => 13, 'header' => 'Every page'],
    ['sl' => 14, 'title' => 'Header & footer', 'icon' => 'fas fa-window-maximize', 'children' => [
        ['title' => 'Contact details, links & social', 'icon' => 'fas fa-sliders', 'route' => 'nit.admin.settings', 'permission' => 'site_settings_edit'],
        $list('footer_resources', 'Footer resources', 'fas fa-book'),
        $list('payment_methods', 'Payment methods', 'fas fa-credit-card'),
    ]],
];
