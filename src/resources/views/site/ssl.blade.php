@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('ssl'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section" aria-labelledby="sslTitle">
        <div class="container">
            <h2 class="visually-hidden" id="sslTitle">{{ $tx('SSL certificates') }}</h2>
            <div class="row g-3" data-stagger="60">
                @foreach($plans as [$name, $price, $pid, $note])
                    <div class="col-md-6 col-lg-4 reveal">
                        <article class="nit-card card-hover nit-card-body d-flex align-items-center gap-3 h-100">
                            <span class="icon-box icon-box-lg flex-shrink-0" aria-hidden="true"><i class="bi {{ $ic('bi-shield-lock') }}"></i></span>
                            <div class="flex-grow-1">
                                <h3 class="h6 mb-1">{{ $name }}</h3>
                                <p class="small mb-1">{{ $note }}</p>
                                <strong class="h5 mb-0 text-body">{{ $price }}</strong><span class="small text-muted">/year</span>
                            </div>
                            <a class="btn btn-sm btn-primary" href="{{ $pid }}" aria-label="Order {{ $name }}">{{ $tx('Order') }}</a>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('nit::partials.cta')
@endsection
