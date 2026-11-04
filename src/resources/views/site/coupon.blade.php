@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('coupon'); @endphp

@section('title', $pg['meta_title'])
@php $coupon = $site['coupon'] ?? []; @endphp
@section('description', $pg['description'] ?: 'Natore-IT coupon: ' . ($coupon['headline'] ?? '') . ' ' . ($coupon['product'] ?? ''))

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section">
        <div class="container" style="max-width: 560px">
            <div class="nit-card nit-card-body text-center reveal">
                <span class="icon-box icon-box-lg mb-3" aria-hidden="true"><i class="bi bi-ticket-perforated"></i></span>
                <h2 class="h1 mb-1">{{ $coupon['headline'] ?? '' }}</h2>
                <p class="mb-3">{{ $coupon['product'] ?? '' }}</p>
                <div class="coupon-code mx-auto">
                    <label class="visually-hidden" for="couponPageCode">Coupon code</label>
                    <input class="form-control text-center" id="couponPageCode" value="{{ $coupon['code'] ?? '' }}" readonly>
                    <button type="button" class="btn btn-primary" data-copy="#couponPageCode"><i class="bi bi-clipboard" aria-hidden="true"></i><span data-copy-label>Copy</span></button>
                </div>
                <p class="small text-muted mt-2 mb-3" data-copy-status role="status" aria-live="polite"></p>
                <a class="btn btn-outline-primary" href="{{ $coupon['order_url'] ?? '#' }}">{{ $coupon['order_label'] ?? 'Order now' }} <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></a>
            </div>
        </div>
    </section>
@endsection
