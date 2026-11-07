@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('reviews'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@php
    $avg = round(collect($reviews)->avg('rating'), 1);
    $breakdown = [5 => 9, 4 => 1, 3 => 0, 2 => 0, 1 => 0]; // as published on natoreit.com
    $total = count($reviews);
@endphp

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section" aria-labelledby="ratingTitle">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="nit-card nit-card-body rating-summary sticky-aside reveal">
                        <h2 class="h6 text-muted text-uppercase mb-2" id="ratingTitle">{{ $tx('User rating') }}</h2>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="rating-big">{{ number_format($avg, 1) }}</span>
                            <span class="stars" aria-label="{{ $avg }} out of 5 stars">@for($i = 1; $i <= 5; $i++)<i class="bi {{ $ic('bi-star-fill') }}" aria-hidden="true"></i>@endfor</span>
                        </div>
                        <p class="small mb-3">Average based on {{ $total }} reviews</p>
                        @foreach($breakdown as $stars => $n)
                            <div class="rating-row">
                                <span>{{ $stars }} star</span>
                                <span class="rating-bar"><span style="width: {{ $total ? round($n / $total * 100) : 0 }}%"></span></span>
                                <span>{{ $n }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3" data-stagger="40">
                        @foreach($reviews as $r)
                            <div class="col-md-6 reveal">
                                <figure class="nit-card nit-card-body h-100 mb-0 review-card">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="stars small" aria-label="{{ $r['rating'] }} out of 5 stars">@for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= $r['rating'] ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor</span>
                                        <span class="small text-muted">{{ ucfirst($r['date']) }}</span>
                                    </div>
                                    <h3 class="h6">{{ ucfirst($r['title']) }}</h3>
                                    <blockquote class="small mb-3"><p class="mb-0">{{ $r['text'] }}</p></blockquote>
                                    <figcaption class="d-flex align-items-center gap-2 small fw-semibold">
                                        <span class="avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($r['author'], 0, 1)) }}</span>{{ $r['author'] }}
                                    </figcaption>
                                </figure>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('nit::partials.cta')
@endsection
