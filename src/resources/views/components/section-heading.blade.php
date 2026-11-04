@props(['eyebrow' => null, 'title', 'center' => false, 'id' => null])
<div {{ $attributes->class(['section-heading reveal', 'text-center' => $center]) }}>
    @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
    <h2 class="h1 mb-3" @if($id) id="{{ $id }}" @endif>{{ $title }}</h2>
    @if(trim($slot) !== '')<p>{{ $slot }}</p>@endif
</div>
