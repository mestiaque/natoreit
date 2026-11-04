@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('domain'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
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
                    <li class="tld-item"><span class="tld-name">{{ $d['tld'] }}</span><span class="tld-price">{{ $d['price'] }}<small>{{ $d['period'] }}</small></span></li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section bg-soft" aria-label="Domain accounts">
        <div class="container">
            <div class="row g-3 mb-4" data-stagger="50">
                @foreach($links as [$label, $url, $icon])
                    <div class="col-6 col-lg-3 reveal">
                        <a class="nit-card card-hover nit-card-body d-flex align-items-center gap-2 h-100 text-decoration-none" href="{{ $url }}" target="_blank" rel="noopener">
                            <span class="icon-box flex-shrink-0" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                            <span class="fw-semibold text-body small">{{ $label }}<span class="visually-hidden"> (opens in a new tab)</span></span>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="row g-4">
                <div class="col-lg-6 reveal">
                    <div class="nit-card nit-card-body h-100">
                        <span class="icon-box mb-2" aria-hidden="true"><i class="bi bi-globe"></i></span>
                        <h2 class="h4">Domain registration</h2>
                        <p class="mb-0">Choose your domain name as per your business requirements and take your business online. The perfect domain name attracts visitors. Find popular top-level domains at affordable prices. Start your website with Natore-IT today.</p>
                    </div>
                </div>
                <div class="col-lg-6 reveal">
                    <div class="nit-card nit-card-body h-100">
                        <span class="icon-box mb-2" aria-hidden="true"><i class="bi bi-people"></i></span>
                        <h2 class="h4">Domain reseller</h2>
                        <p class="mb-0">A domain reseller is a company or person that purchases domains from a domain registrar or another domain reseller for the benefit of their clients. Sign up as a Natore-IT domain reseller and sell domains under your own brand.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('nit::partials.cta')
@endsection
