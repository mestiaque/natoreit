@extends('nit::layouts.site')

@section('title', $page['title'])
@section('description', $page['lead'])

@php
    $periods = ['/Y' => '/year', '/M' => '/month'];
    $money = fn ($price) => preg_replace('/\.00$/', '', $price);
    $count = count($page['plans']);
    $col = $count === 4 ? 'col-md-6 col-xl-3' : 'col-md-6 col-lg-4';
    $cell = function ($value) {
        if ($value === true) return '<i class="bi bi-check-circle-fill compare-yes" aria-hidden="true"></i><span class="visually-hidden">Included</span>';
        if ($value === false) return '<i class="bi bi-dash-lg compare-no" aria-hidden="true"></i><span class="visually-hidden">Not included</span>';
        return e($value);
    };
@endphp

@section('content')
    @include('nit::partials.page-hero', ['title' => $page['title'], 'lead' => $page['lead'], 'eyebrow' => $page['eyebrow'] ?? 'Hosting'])

    <section class="section" aria-labelledby="plansTitle">
        <div class="container">
            <x-nit::section-heading :title="$page['heading']" :center="true" id="plansTitle">
                Pick a plan below — every order is completed securely on our billing system.
            </x-nit::section-heading>
            <div class="row g-4 justify-content-center pt-3" data-stagger="70">
                @foreach($page['plans'] as $i => $plan)
                    <div class="{{ $col }} reveal">
                        <article class="nit-card card-hover package-card plan-card {{ $plan['featured'] ? 'is-featured' : '' }}">
                            @if($plan['featured'])<span class="pricing-badge"><i class="bi bi-star-fill me-1" aria-hidden="true"></i>Best value</span>@endif
                            <header class="package-head">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="package-icon" aria-hidden="true"><i class="bi {{ $page['icon'] }}"></i></span>
                                    <h3 class="package-name">{{ $plan['name'] }}</h3>
                                </div>
                                <div class="package-price">
                                    <span class="price">{{ $money($plan['price']) }}</span>
                                    <span class="package-meta">
                                        <del><span class="visually-hidden">Regular price </span>{{ $money($plan['regular']) }}</del>
                                        <small>{{ $periods[$plan['period']] ?? $plan['period'] }}</small>
                                    </span>
                                </div>
                            </header>
                            <div class="package-body">
                                <ul class="check-list small">
                                    @foreach(array_slice($page['rows'], 0, 6) as $row)
                                        @php $v = $row[$i + 1]; @endphp
                                        <li>@if($v === true){{ $row[0] }}@else<strong>{{ $v }}</strong> {{ $row[0] }}@endif</li>
                                    @endforeach
                                </ul>
                                <a class="btn {{ $plan['featured'] ? 'btn-primary' : 'btn-outline-primary' }} w-100" href="{{ $plan['order'] }}"
                                   aria-label="Buy the {{ $plan['name'] }} {{ $page['title'] }} plan">
                                    Buy now <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section bg-soft" aria-labelledby="compareTitle">
        <div class="container">
            <x-nit::section-heading :title="$page['title'] . ' — all features'" :center="true" id="compareTitle" />
            <button type="button" class="btn btn-outline-primary w-100 d-md-none compare-toggle" data-more-toggle="#compareWrap" aria-expanded="false" aria-controls="compareWrap">
                <span data-more-label>Compare all {{ count($page['rows']) }} features</span> <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="nit-card overflow-hidden reveal compare-wrap" id="compareWrap" data-more-list>
                <div class="table-responsive">
                    <table class="table compare-table mb-0 text-center">
                        <caption class="visually-hidden">Feature comparison of {{ $page['title'] }} plans</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="text-start" style="min-width: 200px">Feature</th>
                                @foreach($page['plans'] as $plan)
                                    <th scope="col" class="{{ $plan['featured'] ? 'is-featured-col' : '' }}" style="min-width: 120px">
                                        {{ $plan['name'] }}<span class="d-block small text-muted fw-semibold">{{ $money($plan['price']) }}{{ $periods[$plan['period']] ?? '' }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($page['rows'] as $row)
                                <tr>
                                    <th scope="row" class="text-start fw-semibold">{{ $row[0] }}</th>
                                    @foreach($page['plans'] as $i => $plan)
                                        <td class="{{ $plan['featured'] ? 'is-featured-col' : '' }}">{!! $cell($row[$i + 1] ?? false) !!}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row" class="text-start"></th>
                                @foreach($page['plans'] as $plan)
                                    <td class="{{ $plan['featured'] ? 'is-featured-col' : '' }}">
                                        <a class="btn btn-sm {{ $plan['featured'] ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ $plan['order'] }}">Buy now</a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @if($page['includes_title'])
        <section class="section" aria-labelledby="includesTitle">
            <div class="container">
                <x-nit::section-heading :title="$page['includes_title']" :center="true" id="includesTitle">
                    {{ $page['about'][0] ?? '' }}
                </x-nit::section-heading>
                <div class="row g-3" data-stagger="40">
                    @foreach($includes as [$icon, $label])
                        <div class="col-6 col-md-4 col-lg-fifth reveal">
                            <div class="nit-card card-hover nit-card-body text-center include-card">
                                <span class="icon-box mb-2" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                                <h3 class="h6 mb-0">{{ $label }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
                @foreach(array_slice($page['about'] ?? [], 1) as $paragraph)
                    <p class="text-muted text-center mx-auto mt-4 mb-0 reveal" style="max-width: 760px">{{ $paragraph }}</p>
                @endforeach
            </div>
        </section>
    @endif

    @include('nit::partials.cta')
@endsection
