{{-- Social profile icons; profiles without a link (admin › Website settings) are left out. Vars: class, itemClass --}}
@php
    $profiles = ['facebook' => ['bi-facebook', 'Facebook'], 'twitter' => ['bi-twitter-x', 'X (Twitter)'], 'linkedin' => ['bi-linkedin', 'LinkedIn'], 'youtube' => ['bi-youtube', 'YouTube']];
    $profiles = array_filter($profiles, fn ($p, $key) => ($site['social'][$key] ?? '#') !== '#' && filled($site['social'][$key] ?? null), ARRAY_FILTER_USE_BOTH);
@endphp
@if($profiles)
    <ul class="{{ $class }}" aria-label="Social media">
        @foreach($profiles as $key => [$icon, $label])
            <li><a @if($itemClass) class="{{ $itemClass }}" @endif href="{{ $site['social'][$key] }}" target="_blank" rel="noopener" aria-label="{{ $label }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></a></li>
        @endforeach
    </ul>
@endif
