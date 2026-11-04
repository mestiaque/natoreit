@php
    // Main menu mirrors natoreit.com
    // Hosting menu: the plan pages whose address contains "hosting" (admin › Plan pages)
    $hostingMenu = collect(\ME\Nit\Support\Content::planPages())
        ->filter(fn ($page, $pageSlug) => str_contains($pageSlug, 'hosting'))
        ->map(fn ($page, $pageSlug) => [$pageSlug, $page['title'], ($page['menu_text'] ?? null) ?: $page['lead'], $page['icon']])
        ->values()->all();
    $designMenu = [
        ['nit.site.web-design', 'Web Design Packages', 'bi-palette'],
        ['nit.site.multi-vendor', 'Multi Vendor Ecommerce', 'bi-shop-window'],
        ['nit.site.pos', 'POS Software', 'bi-upc-scan'],
        ['nit.site.crm', 'CRM Software', 'bi-people'],
        ['nit.site.software-solution', 'Software Solution', 'bi-code-square'],
    ];
    $links = [
        ['VPS', route('nit.site.plans', 'vps'), request()->is('vps')],
        ['SSL', route('nit.site.ssl'), request()->routeIs('nit.site.ssl')],
        ['Domain', route('nit.site.domain'), request()->routeIs('nit.site.domain')],
        ['SEO', route('nit.site.plans', 'seo'), request()->is('seo')],
        ['About', route('nit.site.about'), request()->routeIs('nit.site.about')],
        ['Portfolio', route('nit.site.portfolio'), request()->routeIs('nit.site.portfolio')],
        ['Blog', $site['links']['blog'] ?? 'https://blog.natoreit.com', false],
        ['Review', route('nit.site.reviews'), request()->routeIs('nit.site.reviews')],
        ['Contact', route('nit.site.contact'), request()->routeIs('nit.site.contact')],
    ];
    $slug = request()->route('slug');
    $hostingActive = in_array($slug, array_column($hostingMenu, 0), true);
    $designActive = request()->routeIs(array_column($designMenu, 0));
@endphp
<header class="site-header sticky-top" data-site-header>
    <nav class="navbar navbar-expand-xl" aria-label="Main">
        <div class="container">
            <a class="brand" href="{{ route('nit.site.home') }}">
                <img class="brand-logo" src="{{ \ME\Nit\Support\Asset::url('img/natore-it-logo.png') }}" alt="{{ $site['name'] }} — web hosting &amp; design" width="453" height="87">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav"
                    aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bars" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>

            <div class="collapse navbar-collapse site-nav" id="siteNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('nit.site.home')) active @endif" href="{{ route('nit.site.home') }}"
                           @if(request()->routeIs('nit.site.home')) aria-current="page" @endif>Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle @if($hostingActive) active @endif" type="button" data-bs-toggle="dropdown" aria-expanded="false">Hosting</button>
                        <div class="dropdown-menu dropdown-menu-wide">
                            @foreach($hostingMenu as [$itemSlug, $label, $sub, $icon])
                                <a class="dropdown-item dropdown-rich @if($slug === $itemSlug) active @endif" href="{{ route('nit.site.plans', $itemSlug) }}"
                                   @if($slug === $itemSlug) aria-current="page" @endif>
                                    <span class="icon-box" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                                    <span><strong>{{ $label }}</strong><small>{{ $sub }}</small></span>
                                </a>
                            @endforeach
                            <div class="dropdown-footer">
                                <a class="link-arrow" href="https://billing.natoreit.com/cart.php">Our all packages <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle @if($designActive) active @endif" type="button" data-bs-toggle="dropdown" aria-expanded="false">Web Design</button>
                        <div class="dropdown-menu">
                            @foreach($designMenu as [$routeName, $label, $icon])
                                <a class="dropdown-item d-flex align-items-center gap-2 @if(request()->routeIs($routeName)) active @endif" href="{{ route($routeName) }}"
                                   @if(request()->routeIs($routeName)) aria-current="page" @endif>
                                    <i class="bi {{ $icon }} text-primary" aria-hidden="true"></i> {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    @foreach($links as [$label, $url, $active])
                        <li class="nav-item">
                            <a class="nav-link @if($active) active @endif" href="{{ $url }}" @if($active) aria-current="page" @endif
                               @if(str_starts_with($url, 'https://blog.')) target="_blank" rel="noopener" @endif>{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</header>
