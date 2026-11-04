@extends('nit::master')

@section('title', __('Website settings'))

@include('nit::elements.page-ui')

@push('buttons')
    <a href="{{ route('nit.site.contact') }}" target="_blank" rel="noopener" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i> {{ __('Contact page') }}</a>
@endpush

@section('content')
<div class="nx-page">
    @include('nit::admin.shown-on', ['shownOn' => \ME\Nit\Support\AdminMap::links('settings')])
    <form id="siteSettingsForm" method="POST" action="{{ route('nit.admin.settings.update') }}" data-nx-once novalidate>
        @csrf
        @method('PUT')
        @foreach($groups as $title => $fields)
            <x-nit::card :title="__($title)" class="mb-5">
                <div class="nx-form-grid">
                    @foreach($fields as $key => $field)
                        @php
                            [$label, $rules] = $field;
                            $type = $field[2] ?? 'text';
                            $hint = isset($field[3]) ? __($field[3]) : null;
                            $parts = explode('.', $key);
                            $name = $parts[0] . (isset($parts[1]) ? '[' . $parts[1] . ']' : '');
                            $value = data_get($site, $key);
                            if ($type === 'lines') $value = implode("\n", (array) $value);
                            if (str_starts_with($key, 'social.') && $value === '#') $value = '';
                            $required = in_array('required', $rules, true);
                        @endphp
                        @if(in_array($type, ['textarea', 'lines'], true))
                            <x-nit::textarea :name="$name" :label="__($label)" :value="$value" :hint="$hint" rows="3" :required="$required" />
                        @else
                            <x-nit::input :name="$name" :label="__($label)" :type="$type" :value="$value" :hint="$hint" :required="$required" />
                        @endif
                    @endforeach
                </div>
            </x-nit::card>
        @endforeach
        <div class="d-flex justify-content-end">
            <x-nit::button icon="fas fa-check">{{ __('Save settings') }}</x-nit::button>
        </div>
    </form>
</div>
@endsection
