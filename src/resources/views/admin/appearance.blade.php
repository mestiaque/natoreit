@extends('nit::master')

@section('title', __('Appearance'))

@include('nit::elements.page-ui')

@push('buttons')
    <a href="{{ route('nit.site.home') }}" target="_blank" rel="noopener" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-up-right-from-square me-1"></i>{{ __('View website') }}</a>
@endpush

@section('content')
<div class="nx-page">
    <form id="siteAppearanceForm" method="POST" action="{{ route('nit.admin.appearance.update') }}" enctype="multipart/form-data" data-nx-once novalidate>
        @csrf
        @method('PUT')

        <x-nit::card :title="__('Logo & browser icon')" class="mb-5">
            <div class="nx-form-grid">
                @foreach(['logo' => 'Logo', 'favicon' => 'Browser tab icon'] as $name => $label)
                    @php $current = $site['logo'][$name] ?? null; @endphp
                    <div>
                        <label class="form-label" for="{{ $name }}">{{ __($label) }}</label>
                        @if($current)
                            <div class="mb-2"><img src="{{ \ME\Nit\Support\Content::mediaUrl($current) }}" alt="" style="max-height: 48px"></div>
                            <label class="d-block mb-2 small"><input type="checkbox" name="{{ $name }}_remove" value="1"> {{ __('Remove and use the original') }}</label>
                        @endif
                        <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/png,image/jpeg,image/webp" class="form-control @error($name) is-invalid @enderror">
                        @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </x-nit::card>

        @foreach(\ME\Nit\Support\Theme::FIELDS as $group => $fields)
            <x-nit::card :title="__($group)" class="mb-5">
                <div class="nx-form-grid">
                    @foreach($fields as $key => $field)
                        @php
                            $name = "theme[$key]";
                            $label = __($field[0]);
                            $hint = isset($field[4]) ? __($field[4]) : null;
                            $value = old("theme.$key", $values[$key]);
                        @endphp
                        @if($field[1] === 'font')
                            <x-nit::select :name="$name" :label="$label" :value="$value" :options="array_combine(\ME\Nit\Support\Theme::FONTS, \ME\Nit\Support\Theme::FONTS)" :hint="$hint" />
                        @elseif($field[1] === 'select')
                            <x-nit::select :name="$name" :label="$label" :value="$value" :options="$field[3]" :hint="$hint" />
                        @elseif($field[1] === 'number')
                            <x-nit::input :name="$name" :label="$label" type="number" :value="$value" :hint="$hint" :step="$field[3][2]" />
                        @else
                            <x-nit::input :name="$name" :label="$label" type="color" :value="$value" :hint="$hint" />
                        @endif
                    @endforeach
                </div>
            </x-nit::card>
        @endforeach

        @foreach(\ME\Nit\Support\SiteTexts::GROUPS as $group => $texts)
            <x-nit::card :title="__('Texts') . ': ' . __($group)" class="mb-5">
                <div class="nx-form-grid">
                    @foreach($texts as $default)
                        @php $hash = md5($default); @endphp
                        @if(mb_strlen($default) > 90)
                            <x-nit::textarea name="texts[{{ $hash }}]" :label="\Illuminate\Support\Str::limit($default, 60)" :value="$site['texts'][$hash] ?? $default" rows="3" />
                        @else
                            <x-nit::input name="texts[{{ $hash }}]" :label="$default" :value="$site['texts'][$hash] ?? $default" />
                        @endif
                    @endforeach
                </div>
            </x-nit::card>
        @endforeach

        <x-nit::card :title="__('Icons')" class="mb-5">
            <p class="nx-hint mb-3">{{ __('Write a Bootstrap icon name such as bi-star. All names are on icons.getbootstrap.com.') }}</p>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
            <div class="nx-form-grid">
                @foreach(\ME\Nit\Support\SiteTexts::ICONS as $icon)
                    <div class="d-flex align-items-end gap-2">
                        <i class="bi {{ $site['icons'][$icon] ?? $icon }} fs-4 mb-2" aria-hidden="true"></i>
                        <div class="flex-grow-1"><x-nit::input name="icons[{{ $icon }}]" :label="$icon" :value="$site['icons'][$icon] ?? $icon" /></div>
                    </div>
                @endforeach
            </div>
        </x-nit::card>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" form="siteAppearanceReset" class="btn btn-light">{{ __('Reset design') }}</button>
            <x-nit::button icon="fas fa-check">{{ __('Save appearance') }}</x-nit::button>
        </div>
    </form>
    <form id="siteAppearanceReset" method="POST" action="{{ route('nit.admin.appearance.reset') }}" onsubmit="return confirm(@js(__('Reset colors, fonts, buttons and animation to the original design?')))">@csrf</form>
</div>
@endsection
