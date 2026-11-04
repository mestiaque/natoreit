@extends('nit::master')

@section('title', $record->exists ? __('Edit :item', ['item' => __($singular)]) : __('Add :item', ['item' => __($singular)]))

@include('nit::elements.page-ui')

@push('buttons')
    <a href="{{ $extra['back_url'] ?? route($route . '.index') }}" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('Back') }}</a>
@endpush

@section('content')
<div class="nx-page">
    @include('nit::admin.shown-on')
    <form id="siteResourceForm" method="POST" enctype="multipart/form-data" data-nx-once novalidate
          action="{{ $record->exists ? route($route . '.update', $record->id) : route($route . '.store') }}">
        @csrf
        @if ($record->exists) @method('PUT') @endif
        <x-nit::card :title="$record->exists ? $record->activityLabel() : __('Add :item', ['item' => __($singular)])" :subtitle="isset($extra['form_note']) ? __($extra['form_note']) : null">
            <div class="nx-form-grid">
                @foreach($fields as $name => $field)
                    @include('nit::admin.resource.field', ['name' => $name, 'field' => $field, 'value' => $record->{$name}])
                @endforeach
            </div>
            @isset($extra['form_partial'])
                @include($extra['form_partial'])
            @endisset
            <x-slot:footer>
                <x-nit::button variant="light" :href="$extra['back_url'] ?? route($route . '.index')">{{ __('Cancel') }}</x-nit::button>
                <x-nit::button icon="fas fa-check">{{ __('Save') }}</x-nit::button>
            </x-slot:footer>
        </x-nit::card>
    </form>
</div>
@endsection
