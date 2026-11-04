{{-- Inner-page hero. Expects: $title, $lead; optional: $eyebrow, $crumbs (label => url) --}}
<section class="page-hero" data-hero-end>
    <div class="container">
        <nav aria-label="Breadcrumb" class="enter">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('nit.site.home') }}">Home</a></li>
                @foreach($crumbs ?? [] as $label => $url)
                    <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol>
        </nav>
        @isset($eyebrow)<p class="eyebrow enter enter-1">{{ $eyebrow }}</p>@endisset
        <h1 class="enter enter-1">{{ $title }}</h1>
        <p class="lead mb-0 enter enter-2">{{ $lead }}</p>
    </div>
</section>
