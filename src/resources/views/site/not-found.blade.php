@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('not_found'); @endphp

@section('title', $pg['meta_title'])

@section('content')
    <section class="section not-found">
        <div class="container text-center" style="max-width: 620px">
            <p class="not-found-code" aria-hidden="true">{{ $pg['eyebrow'] }}</p>
            <h1 class="h2">{{ $pg['title'] }}</h1>
            <p class="mb-4">{{ $pg['lead'] }}</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a class="btn btn-primary" href="{{ route('nit.site.home') }}">{{ $tx('Back to home') }}</a>
                <a class="btn btn-outline-primary" href="{{ route('nit.site.software-solution') }}">{{ $tx('All Software') }}</a>
                <a class="btn btn-outline-primary" href="{{ route('nit.site.contact') }}">{{ $tx('Contact us') }}</a>
            </div>
        </div>
    </section>
@endsection
