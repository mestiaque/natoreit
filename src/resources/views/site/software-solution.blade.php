@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('software_solution'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section" aria-labelledby="solutionsTitle">
        <div class="container">
            <x-nit::section-heading title="Our software solutions" :center="true" id="solutionsTitle">
                Responsive, SEO-friendly web applications for education, real estate, construction, e-commerce, health, news, travel and more.
            </x-nit::section-heading>
            <div class="row g-3" data-stagger="40">
                @foreach($items as [$icon, $label])
                    <div class="col-6 col-md-4 col-lg-3 reveal">
                        <a class="nit-card card-hover nit-card-body d-flex flex-column align-items-start h-100 text-decoration-none" href="{{ route('nit.site.contact', ['subject' => 'software', 'product' => $label]) }}">
                            <span class="icon-box mb-2" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                            <h3 class="h6 mb-1 text-body">{{ $label }}</h3>
                            <span class="small text-primary fw-semibold mt-auto">Get a quote <i class="bi bi-arrow-right card-arrow" aria-hidden="true"></i></span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('nit::partials.cta', ['ctaHeading' => 'Need custom software?', 'ctaText' => 'Tell us how your business works and we will build the system around it.', 'ctaUrl' => route('nit.site.contact'), 'ctaLabel' => 'Get a quote'])
@endsection
