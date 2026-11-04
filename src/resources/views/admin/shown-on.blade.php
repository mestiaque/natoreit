{{-- "Shown on" bar: which website pages this admin page changes. Vars: $shownOn [label => url] --}}
@if(! empty($shownOn))
    <div class="nx-panel d-flex flex-wrap align-items-center gap-2 px-4 py-3 mb-4">
        <span class="fw-semibold me-1"><i class="fas fa-eye me-1 text-primary" aria-hidden="true"></i>{{ __('Shown on the website:') }}</span>
        @foreach($shownOn as $label => $url)
            <a href="{{ $url }}" target="_blank" rel="noopener" class="nx-pill nx-pill-primary text-decoration-none">{{ __($label) }} <i class="fas fa-arrow-up-right-from-square ms-1" style="font-size: .7em" aria-hidden="true"></i></a>
        @endforeach
    </div>
@endif
