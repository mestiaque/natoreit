@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('multi_vendor'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@php
    // Spelling fixes for headings copied from natoreit.com
    $fix = fn ($t) => strtr($t, ['Fetured' => 'Featured', 'getaway' => 'gateway', 'Uploard' => 'Upload', 'Configration' => 'Configuration', 'conformation' => 'confirmation']);
    // Split groups into blocks: a block starts at each top-level heading (no items)
    $blocks = [['title' => 'Our Multi Vendor Ecommerce All Featured For You!', 'groups' => []]];
    foreach ($data['groups'] as $g) {
        if (! empty($g['heading']) || (count($g['items']) === 0)) { $blocks[] = ['title' => $fix($g['title']), 'groups' => []]; continue; }
        $blocks[array_key_last($blocks)]['groups'][] = $g;
    }
@endphp

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead'] ?: ($data['intro'][0] ?? '')])

    @foreach($blocks as $b => $block)
        @continue(empty($block['groups']))
        <section class="section {{ $b % 2 ? 'bg-soft' : '' }}" aria-labelledby="mve-{{ $b }}">
            <div class="container">
                <x-nit::section-heading :title="$block['title']" :center="true" :id="'mve-' . $b">
                    @if($b === 0){{ $data['intro'][1] ?? '' }}@endif
                </x-nit::section-heading>
                <div class="feature-masonry" data-stagger="40">
                    @foreach($block['groups'] as $g)
                        <div class="nit-card nit-card-body feature-group reveal">
                            <h3 class="h6">{{ $fix($g['title'] === 'Our Multi Vendor Ecommerce All Fetured For You!' ? 'Project phases' : $g['title']) }}</h3>
                            <ul class="check-list small mb-0">
                                @foreach($g['items'] as $item)<li>{{ $fix($item) }}</li>@endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    @include('nit::partials.cta', ['ctaHeading' => 'Planning a marketplace?', 'ctaText' => 'Tell us about your vendors and products — we will share a proposal and timeline.', 'ctaUrl' => route('nit.site.contact'), 'ctaLabel' => 'Get a quote'])
@endsection
