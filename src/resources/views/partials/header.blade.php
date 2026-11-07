@php
    // Main menu mirrors natoreit.com
    // Hosting menu: the plan pages whose address contains "hosting" (admin › Plan pages)
    $hostingMenu = collect(\ME\Nit\Support\Content::planPages())
        ->filter(fn ($page, $pageSlug) => str_contains($pageSlug, 'hosting'))
        ->map(fn ($page, $pageSlug) => [$pageSlug, $page['title'], ($page['menu_text'] ?? null) ?: $page['lead'], $page['icon']])
        ->values()->all();
    // Dropdowns: label => [[url, label, icon, active]]
    $routeItem = fn (string $name, string $label, string $icon) => [route($name), $label, $icon, request()->routeIs($name)];
    $menus = [
        $tx('Web Design') => [
            $routeItem('nit.site.web-design', $tx('Web Design Packages'), $ic('bi-palette')),
            $routeItem('nit.site.multi-vendor', $tx('Multi Vendor Ecommerce'), $ic('bi-shop-window')),
            [route('nit.site.plans', 'seo'), $tx('SEO'), $ic('bi-graph-up-arrow'), request()->is('seo')],
        ],
        $tx('Software') => [
            $routeItem('nit.site.erp', $tx('ERP Software'), $ic('bi-diagram-3')),
            $routeItem('nit.site.pos', $tx('POS Software'), $ic('bi-upc-scan')),
            $routeItem('nit.site.crm', $tx('CRM Software'), $ic('bi-people')),
            $routeItem('nit.site.custom', $tx('Custom Web Application'), $ic('bi-code-square')),
            $routeItem('nit.site.software-solution', $tx('All Software'), $ic('bi-grid')),
        ],
    ];
    $links = [
        [$tx('VPS'), route('nit.site.plans', 'vps'), request()->is('vps')],
        [$tx('SSL'), route('nit.site.ssl'), request()->routeIs('nit.site.ssl')],
        [$tx('Domain'), route('nit.site.domain'), request()->routeIs('nit.site.domain')],
        [$tx('About'), route('nit.site.about'), request()->routeIs('nit.site.about')],
        [$tx('Portfolio'), route('nit.site.portfolio'), request()->routeIs('nit.site.portfolio')],
        [$tx('Blog'), $site['links']['blog'] ?? 'https://blog.natoreit.com', false],
        [$tx('Review'), route('nit.site.reviews'), request()->routeIs('nit.site.reviews')],
        [$tx('Contact'), route('nit.site.contact'), request()->routeIs('nit.site.contact')],
    ];
    $slug = request()->route('slug');
    $hostingActive = in_array($slug, array_column($hostingMenu, 0), true);
@endphp
<header class="site-header sticky-top" data-site-header>
    <nav class="navbar navbar-expand-xl" aria-label="Main">
        <div class="container">
            <a class="brand" href="{{ route('nit.site.home') }}">
                <img class="brand-logo" src="{{ filled($site['logo']['logo'] ?? null) ? \ME\Nit\Support\Content::mediaUrl($site['logo']['logo']) : \ME\Nit\Support\Asset::url('img/natore-it-logo.png') }}" alt="{{ $site['name'] }} — web hosting &amp; design" width="453" height="87">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav"
                    aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bars" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>

            <div class="collapse navbar-collapse site-nav" id="siteNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('nit.site.home')) active @endif" href="{{ route('nit.site.home') }}"
                           @if(request()->routeIs('nit.site.home')) aria-current="page" @endif>{{ $tx('Home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle @if($hostingActive) active @endif" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $tx('Hosting') }}</button>
                        <div class="dropdown-menu dropdown-menu-wide">
                            @foreach($hostingMenu as [$itemSlug, $label, $sub, $icon])
                                <a class="dropdown-item dropdown-rich @if($slug === $itemSlug) active @endif" href="{{ route('nit.site.plans', $itemSlug) }}"
                                   @if($slug === $itemSlug) aria-current="page" @endif>
                                    <span class="icon-box" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                                    <span><strong>{{ $label }}</strong><small>{{ $sub }}</small></span>
                                </a>
                            @endforeach
                            <div class="dropdown-footer">
                                <a class="link-arrow" href="https://billing.natoreit.com/cart.php">{{ $tx('Our all packages') }} <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </li>
                    @foreach($menus as $menuLabel => $items)
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle @if(in_array(true, array_column($items, 3), true)) active @endif" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ $menuLabel }}</button>
                            <div class="dropdown-menu">
                                @foreach($items as [$itemUrl, $label, $icon, $active])
                                    <a class="dropdown-item d-flex align-items-center gap-2 @if($active) active @endif" href="{{ $itemUrl }}"
                                       @if($active) aria-current="page" @endif>
                                        <i class="bi {{ $icon }} text-primary" aria-hidden="true"></i> {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                    @foreach($links as [$label, $url, $active])
                        <li class="nav-item">
                            <a class="nav-link @if($active) active @endif" href="{{ $url }}" @if($active) aria-current="page" @endif
                               @if(str_starts_with($url, 'https://blog.')) target="_blank" rel="noopener" @endif>{{ $label }}</a>
                        </li>
                    @endforeach
                    @if($theme['quote_button'] === 'on')
                        <li class="nav-item d-none d-xl-flex align-items-center ms-1"><a class="btn btn-primary btn-sm" href="{{ route('nit.site.quote') }}">{{ $tx('Get a quote') }}</a></li>
                    @endif
                    @if($theme['dark_mode'] === 'on')
                        <li class="nav-item d-flex align-items-center">
                            <button type="button" class="theme-toggle" data-theme-toggle aria-label="{{ $tx('Switch dark mode') }}"><i class="bi bi-moon-stars" aria-hidden="true"></i></button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
