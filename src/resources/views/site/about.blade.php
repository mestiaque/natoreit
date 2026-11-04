@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('about'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section" aria-labelledby="whatTitle">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 reveal">
                    <span class="d-block skel rounded-4"><img class="about-img" data-skeleton src="https://natoreit.com/public/images/site-img33.jpg" alt="Natore-IT keeps your website up and running" width="600" height="400" loading="lazy"></span>
                </div>
                <div class="col-lg-6">
                    <p class="eyebrow reveal">What we do</p>
                    <h2 class="h1 mb-3 reveal" id="whatTitle">Web hosting made easy now!</h2>
                    <p class="lead reveal">Natore-IT is a domain, hosting and web design company in Bangladesh. We host, build and look after websites and software for businesses of every size.</p>
                    <div class="d-flex flex-wrap gap-2 reveal">
                        <a class="btn btn-primary" href="{{ route('nit.site.plans', 'shared-hosting') }}">Hosting plans <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></a>
                        <a class="btn btn-outline-primary" href="{{ route('nit.site.web-design') }}">Web design</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-soft" aria-labelledby="whyAboutTitle">
        <div class="container">
            <x-nit::section-heading title="Why choose us?" :center="true" id="whyAboutTitle" />
            <div class="row g-3" data-stagger="50">
                @foreach($why as [$icon, $label])
                    <div class="col-6 col-lg-4 reveal">
                        <div class="nit-card card-hover nit-card-body d-flex align-items-center gap-3 h-100">
                            <span class="icon-box flex-shrink-0" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                            <h3 class="h6 mb-0">{{ $label }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="teamTitle">
        <div class="container">
            <x-nit::section-heading title="Our team" :center="true" id="teamTitle" />
            <div class="row g-3" data-stagger="50">
                @foreach($team as $member)
                    <div class="col-sm-6 col-lg-3 reveal">
                        <article class="nit-card card-hover team-card h-100">
                            <span class="d-block skel team-photo"><img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" width="300" height="300" loading="lazy" data-skeleton></span>
                            <div class="nit-card-body">
                                <h3 class="h6 mb-1">{{ $member['name'] }}</h3>
                                <p class="small text-primary fw-semibold mb-2">{{ $member['role'] }}</p>
                                <p class="small mb-0">{{ $member['bio'] }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('nit::partials.cta')
@endsection
