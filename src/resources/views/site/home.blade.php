@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('home'); @endphp

@if($pg['meta_title'])
    @section('title', $pg['meta_title'])
@endif
@section('description', $pg['description'])

@section('content')
    {{-- 1. Domain search hero --}}
    <section class="hero" data-hero-end>
        <div class="container hero-inner">
            <div class="hero-copy">
                <span class="hero-badge mb-3 enter"><i class="bi {{ $ic('bi-globe2') }}" aria-hidden="true"></i> {{ $pg['eyebrow'] }}</span>
                <h1 class="mb-3 enter enter-1">{{ $pg['title'] }} @if($pg['highlight'] ?? '')<span class="text-gradient">{{ $pg['highlight'] }}</span>@endif</h1>
                <p class="lead mb-4 enter enter-2">{{ $pg['lead'] }}</p>
                @include('nit::partials.domain-search')
                <ul class="tld-strip list-unstyled enter enter-4" aria-label="Domain starting prices">
                    @foreach($domains as $d)
                        <li class="tld-item">
                            <span class="tld-name">{{ $d['tld'] }}</span>
                            <span class="tld-price">{{ $d['price'] }}<small>{{ $d['period'] }}</small></span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @if($theme['hero_3d'] === 'on')
                <div class="hero-visual-3d" aria-hidden="true"><canvas data-hero-globe data-mask="{{ \ME\Nit\Support\Asset::url('img/earth-mask.png') }}"></canvas></div>
            @endif
        </div>
    </section>

    @php $stats = \ME\Nit\Support\Content::list('stats'); @endphp
    @if($stats && $theme['home_stats'] === 'on')
        <section class="stats-bar" aria-label="Natore IT in numbers">
            <div class="container">
                <ul class="list-unstyled row g-2 g-md-3 mb-0">
                    @foreach($stats as [$icon, $number, $label])
                        <li class="col-6 col-md-3">
                            <div class="stat-item reveal">
                                @if($icon)<i class="bi {{ $icon }}" aria-hidden="true"></i>@endif
                                <span><strong data-count>{{ $number }}</strong><small>{{ $label }}</small></span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @php $tech = \ME\Nit\Support\Content::list('tech'); @endphp
    @if($tech && $theme['home_tech'] === 'on')
        <section class="tech-strip" aria-label="{{ $tx('Technologies we work with') }}">
            <p class="tech-title">{{ $tx('Technologies we work with') }}</p>
            <div class="tech-track">
                @foreach([0, 1] as $copy)
                    <ul class="list-unstyled" @if($copy) aria-hidden="true" @endif>
                        @foreach($tech as $name)<li>{{ $name }}</li>@endforeach
                    </ul>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Software we build --}}
    @php $softwareCards = \ME\Nit\Support\Content::list('software_home'); @endphp
    @if($softwareCards && $theme['home_software'] === 'on')
        <section class="section" aria-labelledby="softwareTitle">
            <div class="container">
                <x-nit::section-heading :eyebrow="$tx('Software')" :title="$tx('Software we build')" :center="true" id="softwareTitle">
                    {{ $tx('ERP, POS and custom web applications made for businesses in Bangladesh.') }}
                </x-nit::section-heading>
                <div class="bento" data-stagger="50">
                    @foreach($softwareCards as [$icon, $title, $text, $link])
                        <div class="reveal bento-cell">
                            <a class="nit-card card-hover nit-card-body bento-card" href="{{ url($link) }}">
                                <span class="icon-box mb-3" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                                <h3 class="h6">{{ $title }}</h3>
                                <p class="small mb-0">{{ $text }}</p>
                                <i class="bi bi-arrow-up-right bento-arrow" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @php $steps = \ME\Nit\Support\Content::list('process'); @endphp
    @if($steps && $theme['home_process'] === 'on')
        <section class="process-section" aria-labelledby="processTitle">
            <div class="container">
                <x-nit::section-heading :eyebrow="$tx('How we work')" :title="$tx('From idea to launch, and beyond')" :center="true" id="processTitle" />
                <div class="process-steps">
                    <span class="process-line" data-process-line aria-hidden="true"></span>
                    @foreach($steps as $i => [$icon, $title, $text])
                        <article class="process-step" data-process-step>
                            <span class="process-no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <i class="bi {{ $icon }}" aria-hidden="true"></i>
                            <h3 class="h6">{{ $title }}</h3>
                            <p class="small mb-0">{{ $text }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($theme['home_packages'] === 'on') @include('nit::partials.web-packages') @endif

    @if($theme['home_why'] === 'on') @include('nit::partials.why-choose') @endif

    @if($theme['home_builder'] === 'on') @include('nit::partials.site-builder') @endif

    {{-- 5. What more we offer --}}
    @if($theme['home_offers'] === 'on')
    <section class="section bg-soft" aria-labelledby="offerTitle">
        <div class="container">
            <x-nit::section-heading :eyebrow="$tx('Explore')" :title="$tx('What more we offer')" :center="true" id="offerTitle" />
            <ul class="nav nav-pills-soft justify-content-center mb-4 reveal" role="tablist">
                @php $mocks = ['dashboard', 'website', 'table', 'pos', 'analytics']; @endphp
                @foreach($offers as $key => [$icon, $label])
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="offer-tab-{{ $key }}" data-bs-toggle="pill" data-bs-target="#offer-pane-{{ $key }}"
                                type="button" role="tab" aria-controls="offer-pane-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            <i class="bi {{ $icon }} me-1" aria-hidden="true"></i>{{ $label }}
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content reveal">
                @foreach($offers as $key => [$icon, $label, $text, $points, $link])
                    @php $variant = $mocks[$key % count($mocks)]; @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="offer-pane-{{ $key }}" role="tabpanel" aria-labelledby="offer-tab-{{ $key }}" tabindex="0">
                        <div class="nit-card nit-card-body p-lg-4">
                            <div class="row g-4 align-items-center">
                                <div class="col-lg-5">
                                    <span class="icon-box icon-box-lg mb-3" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                                    <h3 class="h3">{{ $label }}</h3>
                                    <p>{{ $text }}</p>
                                    <ul class="check-list mb-4">
                                        @foreach($points as $point)<li>{{ $point }}</li>@endforeach
                                    </ul>
                                    <a class="btn btn-outline-primary" href="{{ url($link) }}">
                                        {{ $tx('Learn more') }} <span class="visually-hidden">about {{ $label }}</span> <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-lg-7">
                                    <x-nit::mock :variant="$variant" :title="$label" :label="$label . ' preview'" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @endif

    {{-- 6. Featured hosting --}}
    @if($theme['home_hosting'] === 'on')
    <section class="section" aria-labelledby="hostingTitle">
        <div class="container">
            <x-nit::section-heading :eyebrow="$tx('Hosting')" :title="$tx('Hosting for every stage of growth')" :center="true" id="hostingTitle">
                {{ $tx('From your first website to your own hosting business.') }}
            </x-nit::section-heading>
            <div class="row g-4" data-stagger="70">
                @foreach($hosting as [$icon, $title, $text, $points, $link])
                    <div class="col-lg-4 reveal">
                        <article class="nit-card card-hover nit-card-body d-flex flex-column">
                            <span class="icon-box icon-box-lg mb-3" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                            <ul class="check-list small flex-grow-1 mb-4">
                                @foreach($points as $point)<li>{{ $point }}</li>@endforeach
                            </ul>
                            <a class="btn btn-secondary-soft align-self-start" href="{{ url($link) }}">
                                {{ $tx('Read more') }} <span class="visually-hidden">about {{ $title }}</span> <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i>
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @endif

    @if($theme['home_reviews'] === 'on')
        <section class="section bg-soft" aria-labelledby="homeReviewsTitle">
            <div class="container">
                <x-nit::section-heading :eyebrow="$tx('Review')" :title="$tx('What our clients say')" :center="true" id="homeReviewsTitle" />
                <div class="review-rail" tabindex="0" aria-label="{{ $tx('Customer reviews') }}">
                    @foreach(array_slice(\ME\Nit\Support\Content::reviews(), 0, 8) as $review)
                        <figure class="nit-card nit-card-body review-rail-item mb-0">
                            <div class="text-warning mb-2" aria-label="{{ $review['rating'] }} / 5">{{ str_repeat('★', (int) $review['rating']) }}</div>
                            <blockquote class="small mb-2">{{ \Illuminate\Support\Str::limit($review['text'], 150) }}</blockquote>
                            <figcaption class="small fw-bold">{{ $review['author'] ?? '' }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($theme['home_faq'] === 'on') @include('nit::partials.faq-section') @endif

@endsection

@if($theme['hero_3d'] === 'on' || $theme['animations'] === 'on')
    @push('scripts')
        @if($theme['hero_3d'] === 'on')<script src="https://cdn.jsdelivr.net/npm/three@0.149.0/build/three.min.js" defer></script>@endif
        @if($theme['animations'] === 'on')
            <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js" defer></script>
            <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js" defer></script>
        @endif
        <script src="{{ \ME\Nit\Support\Asset::url('js/nit-home.js') }}" defer></script>
    @endpush
@endif
