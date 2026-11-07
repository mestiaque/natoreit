@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('portfolio'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => str_replace(':count', count($items), $pg['lead'])])

    <section class="section" aria-labelledby="projectsTitle">
        <div class="container">
            <h2 class="visually-hidden" id="projectsTitle">{{ $tx('Projects') }}</h2>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div class="filter-bar" role="group" aria-label="Filter projects"
                     data-filter-group="#portfolioGrid" data-filter-status="#portfolioStatus" data-filter-noun="project">
                    <button type="button" class="btn active" data-filter="all" aria-pressed="true">{{ $tx('All') }}</button>
                    @foreach($categories as $key => $label)
                        <button type="button" class="btn" data-filter="{{ $key }}" aria-pressed="false">{{ $label }}</button>
                    @endforeach
                </div>
                <p class="small text-muted mb-0" id="portfolioStatus" role="status" aria-live="polite">Showing {{ count($items) }} projects</p>
            </div>
            <div class="row g-3" id="portfolioGrid" data-page-size="12">
                @foreach($items as $item)
                    <div class="col-6 col-md-4 col-lg-3 filter-item" data-category="{{ implode(' ', $item['categories']) }}">
                        <div class="h-100">
                            <a class="nit-card card-hover portfolio-tile h-100" href="{{ $item['url'] }}" target="_blank" rel="noopener">
                                <span class="card-media skel"><img src="{{ $item['image'] }}" alt="" width="400" height="300" loading="lazy" data-skeleton></span>
                                <span class="portfolio-title">{{ $item['title'] }} <i class="bi {{ $ic('bi-box-arrow-up-right') }}" aria-hidden="true"></i><span class="visually-hidden"> (opens in a new tab)</span></span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row g-3 mt-0 portfolio-loader" data-page-loader hidden aria-hidden="true">
                @for($i = 0; $i < 4; $i++)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="nit-card h-100"><span class="d-block skel" style="aspect-ratio: 4 / 3"></span><span class="d-block p-2"><span class="skel skel-line"></span></span></div>
                    </div>
                @endfor
            </div>
            <div data-page-sentinel></div>
            <p class="visually-hidden" role="status" aria-live="polite" data-page-status></p>
        </div>
    </section>

    @include('nit::partials.cta', ['ctaHeading' => $tx('Want a website like these?'), 'ctaText' => $tx('Pick a web design package or tell us about your project.'), 'ctaUrl' => route('nit.site.web-design'), 'ctaLabel' => $tx('Web design packages')])
@endsection
