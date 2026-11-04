@extends('nit::master')

@section('title', __('Page texts'))

@include('nit::elements.page-ui')

@php
    $addresses = ['home' => 'nit.site.home', 'web_design' => 'nit.site.web-design', 'multi_vendor' => 'nit.site.multi-vendor', 'software_solution' => 'nit.site.software-solution',
        'ssl' => 'nit.site.ssl', 'domain' => 'nit.site.domain', 'about' => 'nit.site.about', 'portfolio' => 'nit.site.portfolio', 'reviews' => 'nit.site.reviews',
        'contact' => 'nit.site.contact', 'coupon' => 'nit.site.coupon'];
    $hasHighlight = array_key_exists('highlight', $defaults);
    $fields = [
        'meta_title'  => ['Browser tab title', 'input', 'Also the title in Google results.'],
        'description' => ['Search description', 'textarea', 'The short text under the title in Google results (about 150 characters).'],
        'eyebrow'     => [$hasHighlight ? 'Badge above the heading' : 'Small label above the heading', 'input', null],
        'title'       => ['Heading', 'input', $hasHighlight ? 'Shown before the coloured words.' : null],
        'highlight'   => ['Coloured words after the heading', 'input', null],
        'lead'        => ['Introduction', 'textarea', $key === 'portfolio' ? ':count is replaced by the number of projects.' : null],
    ];
@endphp

@push('buttons')
    <a href="{{ route($addresses[$key]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i> {{ __('Open the page') }}</a>
@endpush

@section('content')
<div class="nx-page">
    <ul class="nav nav-pills mb-4 flex-wrap gap-1">
        @foreach($pages as $pageKey => $page)
            <li class="nav-item"><a @class(['nav-link', 'active' => $pageKey === $key]) href="{{ route('nit.admin.page-texts.page', $pageKey) }}">{{ __($page['name']) }}</a></li>
        @endforeach
    </ul>

    @include('nit::admin.shown-on', ['shownOn' => [\ME\Nit\Support\AdminMap::PAGES[\ME\Nit\Support\AdminMap::TEXTS[$key]][0] => \ME\Nit\Support\AdminMap::pageUrl(\ME\Nit\Support\AdminMap::TEXTS[$key])]])
    <form id="sitePageTextForm" method="POST" action="{{ route('nit.admin.page-texts.update') }}" data-nx-once novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ $key }}">
        <x-nit::card :title="__($pages[$key]['name'])" :subtitle="__('Leave a field empty to use the default text shown in grey.')">
            <div class="nx-form-grid">
                @foreach($fields as $name => [$label, $type, $hint])
                    @continue($name === 'highlight' && ! $hasHighlight)
                    @php $placeholder = $defaults[$name] ?? ''; $wide = $type === 'textarea' ? 'nx-full' : ''; @endphp
                    @if($type === 'textarea')
                        <x-nit::textarea :name="$name" :label="__($label)" :value="$saved[$name] ?? ''" :hint="$hint ? __($hint) : null" :placeholder="$placeholder" rows="3" wrapper-class="nx-full" />
                    @else
                        <x-nit::input :name="$name" :label="__($label)" :value="$saved[$name] ?? ''" :hint="$hint ? __($hint) : null" :placeholder="$placeholder" />
                    @endif
                @endforeach
            </div>
            <x-slot:footer>
                <x-nit::button icon="fas fa-check">{{ __('Save') }}</x-nit::button>
            </x-slot:footer>
        </x-nit::card>
    </form>
</div>
@endsection
