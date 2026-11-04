@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('home'); @endphp

@if($pg['meta_title'])
    @section('title', $pg['meta_title'])
@endif
@section('description', $pg['description'])

@section('content')
    {{-- 1. Domain search hero --}}
    <section class="hero" data-hero-end>
        <div class="container" style="max-width: 920px">
            <div class="text-center">
                <span class="hero-badge mb-3 enter"><i class="bi bi-globe2" aria-hidden="true"></i> {{ $pg['eyebrow'] }}</span>
                <h1 class="mb-3 enter enter-1">{{ $pg['title'] }} @if($pg['highlight'] ?? '')<span class="text-gradient">{{ $pg['highlight'] }}</span>@endif</h1>
                <p class="lead mx-auto mb-4 enter enter-2">{{ $pg['lead'] }}</p>
            </div>
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
    </section>

    @include('nit::partials.web-packages')

    @include('nit::partials.why-choose')

    @include('nit::partials.site-builder')

    {{-- 5. What more we offer --}}
    <section class="section bg-soft" aria-labelledby="offerTitle">
        <div class="container">
            <x-nit::section-heading eyebrow="Explore" title="What more we offer" :center="true" id="offerTitle" />
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
                                        Learn more <span class="visually-hidden">about {{ $label }}</span> <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i>
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

    {{-- 6. Featured hosting --}}
    <section class="section" aria-labelledby="hostingTitle">
        <div class="container">
            <x-nit::section-heading eyebrow="Hosting" title="Hosting for every stage of growth" :center="true" id="hostingTitle">
                From your first website to your own hosting business.
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
                                Read more <span class="visually-hidden">about {{ $title }}</span> <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i>
                            </a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
