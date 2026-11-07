{{-- Web design packages (home + /web-design). Expects: $packages --}}
    {{-- 2. Web design packages --}}
    <section class="section" id="web-design" aria-labelledby="packagesTitle">
        <div class="container">
            <ul class="service-strip reveal" aria-label="Our services">@foreach(['Web Hosting', 'Reseller Hosting', 'Domain Reg', 'SEO', 'Web Design'] as $service)<li>{{ $service }}</li>@endforeach</ul>
            <x-nit::section-heading :eyebrow="$tx('Web design')" :title="$tx('Web Design Package for you')" :center="true" id="packagesTitle">
                {{ $tx('A complete, ready-to-launch website — domain, hosting, design and business email in one simple one-time price.') }}
            </x-nit::section-heading>
            <div class="row g-4 pt-3" data-stagger="70">
                @foreach($packages as $pkg)
                    @php $save = (int) round(100 - $pkg['price'] / $pkg['regular'] * 100); @endphp
                    <div class="col-md-6 col-xl-3 reveal">
                        <article class="nit-card card-hover package-card {{ $pkg['featured'] ? 'is-featured' : '' }}">
                            @if($pkg['featured'])<span class="pricing-badge"><i class="bi {{ $ic('bi-star-fill') }} me-1" aria-hidden="true"></i>{{ $tx('Best seller') }}</span>@endif
                            <span class="package-save" aria-label="Save {{ $save }} percent">-{{ $save }}%</span>
                            <header class="package-head">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="package-icon" aria-hidden="true"><i class="bi {{ $pkg['icon'] }}"></i></span>
                                    <h3 class="package-name">{{ $pkg['name'] }}</h3>
                                </div>
                                <div class="package-price">
                                    <span class="price">{{ \ME\Nit\Support\SiteData::usd($pkg['price']) }}</span>
                                    <span class="package-meta">
                                        <del><span class="visually-hidden">{{ $tx('Regular price') }} </span>{{ \ME\Nit\Support\SiteData::usd($pkg['regular']) }}</del>
                                        <small>one-time</small>
                                    </span>
                                </div>
                            </header>
                            <div class="package-body">
                            <ul class="check-list small" id="pkgFeatures{{ $loop->index }}" data-more-list>
                                @foreach($pkg['features'] as $feature)<li @if($loop->index >= 5) class="more-item" @endif>{{ $feature }}</li>@endforeach
                            </ul>
                            @if(count($pkg['features']) > 5)
                                <button type="button" class="more-toggle" data-more-toggle="#pkgFeatures{{ $loop->index }}" aria-expanded="false" aria-controls="pkgFeatures{{ $loop->index }}">
                                    <span data-more-label>Show all {{ count($pkg['features']) }} features</span> <i class="bi {{ $ic('bi-chevron-down') }}" aria-hidden="true"></i>
                                </button>
                            @endif
                            <p class="renewal small mb-3"><i class="bi {{ $ic('bi-arrow-repeat') }} me-1" aria-hidden="true"></i>{{ $tx('Renewal:') }} <strong>{{ \ME\Nit\Support\SiteData::usd($pkg['renewal']) }}</strong>/year</p>
                            <a class="btn {{ $pkg['featured'] ? 'btn-primary' : 'btn-outline-primary' }} w-100"
                               href="{{ $pkg['order'] }}"
                               aria-label="Order the {{ $pkg['name'] }} web design package">
                                {{ $tx('Order now') }} <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i>
                            </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

