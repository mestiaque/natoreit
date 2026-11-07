@extends('nit::layouts.site')

@section('title', $titles[0])
@section('description', $data['intro'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => 'Software', 'title' => $titles[1], 'lead' => $data['intro']])

    <section class="section" aria-labelledby="featuresTitle">
        <div class="container">
            <x-nit::section-heading :title="$titles[2]" :center="true" id="featuresTitle" />
            <div class="row g-3" data-stagger="40">
                @foreach($data['sections'] as $i => $section)
                    <div class="col-md-6 col-lg-4 reveal">
                        <div class="nit-card card-hover nit-card-body h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="feature-no" aria-hidden="true">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="h6 mb-0">{{ $section['title'] }}</h3>
                            </div>
                            <ul class="check-list small mb-0">
                                @foreach($section['items'] as $item)<li>{{ $item }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('nit::partials.cta', ['ctaHeading' => $tx('Have any questions?'), 'ctaText' => $tx('Call us on ') . $site['phone'] . ' or send a message — we are here to help.', 'ctaUrl' => route('nit.site.quote', ['service' => $titles[0]]), 'ctaLabel' => $tx('Request a demo')])
@endsection
