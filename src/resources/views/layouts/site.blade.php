<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · {{ $site['name'] }}@else{{ $site['name'] }} — Software, Web & Hosting @endif</title>
    <meta name="description" content="@yield('description', $site['tagline'])">
    <meta name="theme-color" content="#1474b8">

    {{-- Reveal animations are enabled only when JS + IntersectionObserver are available and
         motion is allowed. If nit.js fails to start, the fallback restores visibility. --}}
    <script>
        (function (d) {
            var h = d.documentElement;
            h.classList.add('js');
            if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                h.classList.add('reveal-on');
                setTimeout(function () { if (!window.NIT_READY) h.classList.remove('reveal-on'); }, 2500);
            }
        })(document);
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ \ME\Nit\Support\Asset::url('css/nit.css') }}">
    @stack('head')
</head>
<body>
    <a class="skip-link visually-hidden-focusable" href="#main">Skip to main content</a>
    <div class="scroll-sentinel" data-scroll-sentinel aria-hidden="true"></div>

    @include('nit::partials.topbar')
    @include('nit::partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('nit::partials.footer')
    @include('nit::partials.client-modals')

    <button type="button" class="back-to-top" data-back-to-top tabindex="-1" aria-label="Back to top">
        <i class="bi bi-arrow-up" aria-hidden="true"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="{{ \ME\Nit\Support\Asset::url('js/nit.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
