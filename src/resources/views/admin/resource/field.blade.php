{{-- One form field of the website admin. Vars: name, field [type, label, hint, options, wide, required], value --}}
@php
    $type = $field['type'] ?? 'text';
    $label = __($field['label'] ?? \Illuminate\Support\Str::headline($name));
    $hint = isset($field['hint']) ? __($field['hint']) : null;
    $wide = ($field['wide'] ?? false) || in_array($type, ['textarea', 'lines', 'checkboxes', 'image'], true);
    $required = in_array('required', (array) ($field['rules'] ?? []), true);
    $wrapper = $wide ? 'nx-full' : '';
@endphp

@switch($type)
    @case('textarea')
    @case('lines')
        <x-nit::textarea :name="$name" :label="$label" :value="$value" :hint="$hint" :wrapper-class="$wrapper" :rows="$field['rows'] ?? ($type === 'lines' ? 8 : 4)" :required="$required" />
        @break

    @case('select')
        <x-nit::select :name="$name" :label="$label" :value="$value" :options="$field['options'] ?? []" :hint="$hint" :wrapper-class="$wrapper" :required="$required" />
        @break

    @case('boolean')
        <div class="nx-field {{ $wrapper }}">
            <span class="d-block mb-2 fw-semibold">{{ $label }}</span>
            <input type="hidden" name="{{ $name }}" value="0">
            <label class="nx-check d-inline-flex"><input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $value))> <span>{{ $hint ?: __('Yes') }}</span></label>
        </div>
        @break

    @case('checkboxes')
        <fieldset class="nx-field {{ $wrapper }}">
            <legend class="fs-6 fw-semibold mb-2">{{ $label }}</legend>
            <div class="d-flex flex-wrap gap-3">
                @foreach($field['options'] ?? [] as $optionValue => $optionLabel)
                    <label class="nx-check d-inline-flex"><input type="checkbox" name="{{ $name }}[]" value="{{ $optionValue }}" @checked(in_array($optionValue, array_map('intval', (array) old($name, $value ?? [])), true))> <span>{{ $optionLabel }}</span></label>
                @endforeach
                @if(empty($field['options']))<span class="text-muted">{{ __('Nothing to choose yet.') }}</span>@endif
            </div>
            @if($hint)<p class="nx-hint">{{ $hint }}</p>@endif
        </fieldset>
        @break

    @case('icon')
        <div class="nx-field {{ $wrapper }}">
            <label for="{{ $name }}">{{ $label }}</label>
            <div class="input-group">
                <span class="input-group-text" style="min-width: 2.75rem"><i class="bi {{ old($name, $value) }}" data-icon-preview aria-hidden="true"></i></span>
                <input type="text" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}" class="form-control @error($name) is-invalid @enderror" placeholder="bi-hdd-stack"
                       oninput="this.closest('.input-group').querySelector('[data-icon-preview]').className = 'bi ' + this.value">
            </div>
            <p class="nx-hint">{!! __('A Bootstrap icon name, e.g. bi-hdd-stack. See :link.', ['link' => '<a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">icons.getbootstrap.com</a>']) !!}</p>
            @error($name) <p class="nx-error">{{ $message }}</p> @enderror
        </div>
        @break

    @case('image')
        @php $src = \ME\Nit\Support\Content::mediaUrl($value); $isUrl = $value && preg_match('~^(https?:)?//~', $value); @endphp
        <div class="nx-field {{ $wrapper }}">
            <label for="{{ $name }}">{{ $label }}</label>
            @if($src)
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="{{ $src }}" alt="" class="nx-thumb" style="width: 72px; height: 54px; object-fit: cover">
                    <label class="nx-check d-inline-flex py-1"><input type="checkbox" name="{{ $name }}_remove" value="1"> <span>{{ __('Remove') }}</span></label>
                </div>
            @endif
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/png,image/jpeg,image/webp" class="form-control @error($name) is-invalid @enderror">
                    <p class="nx-hint">{{ __('Upload: PNG, JPG or WEBP · up to 2 MB') }}</p>
                </div>
                <div class="col-md-6">
                    <input type="url" name="{{ $name }}_url" value="{{ old($name . '_url', $isUrl ? $value : '') }}" class="form-control @error($name . '_url') is-invalid @enderror" placeholder="https://…" aria-label="{{ __('or image URL') }}">
                    <p class="nx-hint">{{ __('…or paste an image URL') }}</p>
                </div>
            </div>
            @error($name) <p class="nx-error">{{ $message }}</p> @enderror
            @error($name . '_url') <p class="nx-error">{{ $message }}</p> @enderror
        </div>
        @break

    @default
        <x-nit::input :name="$name" :label="$label" :type="$type" :value="$value" :hint="$hint" :wrapper-class="$wrapper" :required="$required"
            :step="$type === 'number' ? ($field['step'] ?? 'any') : null" />
@endswitch
