<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · {{ $site['name'] }}@else{{ $site['name'] }} — Software, Web & Hosting @endif</title>
    <meta name="description" content="@yield('description', $site['tagline'])">
    <meta name="theme-color" content="#1474b8">
    @include('nit::partials.seo')

    {{-- Reveal animations are enabled only when JS + IntersectionObserver are available and
         motion is allowed. If nit.js fails to start, the fallback restores visibility. --}}
    <script>
        (function (d) {
            var h = d.documentElement;
            if (@json($theme['dark_mode'] === 'on')) {
                try { if (localStorage.getItem('nit-theme') === 'dark') h.setAttribute('data-bs-theme', 'dark'); } catch (e) {}
            }
            h.classList.add('js');
            if (@json($theme['animations'] === 'on') && 'IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                h.classList.add('reveal-on');
                setTimeout(function () { if (!window.NIT_READY) h.classList.remove('reveal-on'); }, 2500);
            }
        })(document);
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ \ME\Nit\Support\Theme::fontsUrl() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ \ME\Nit\Support\Asset::url('css/nit.css') }}">
    @php $themeCss = \ME\Nit\Support\Theme::css(); @endphp
    @if($themeCss !== '')<style>{!! $themeCss !!}</style>@endif
    @if(filled($site['logo']['favicon'] ?? null))<link rel="icon" href="{{ \ME\Nit\Support\Content::mediaUrl($site['logo']['favicon']) }}">@endif
    @stack('head')
    @php $analytics = $site['analytics'] ?? []; @endphp
    @if(filled($analytics['ga'] ?? null))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analytics['ga'] }}"></script>
        <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', @json($analytics['ga']));</script>
    @endif
    @if(filled($analytics['pixel'] ?? null))
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', @json($analytics['pixel']));fbq('track', 'PageView');</script>
    @endif
</head>
<body @if($theme['count_up'] === 'on') data-count-up @endif @if($theme['mobile_bar'] === 'on') class="has-mobile-bar" @endif @if($theme['bdt_rate'] > 0) data-bdt-rate="{{ $theme['bdt_rate'] }}" data-currency-default="{{ $theme['currency_default'] }}" @endif>
    <a class="skip-link visually-hidden-focusable" href="#main">Skip to main content</a>
    @if($theme['scroll_progress'] === 'on')<div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>@endif
    <div class="scroll-sentinel" data-scroll-sentinel aria-hidden="true"></div>

    @include('nit::partials.topbar')
    @include('nit::partials.header')

    <main id="main" tabindex="-1">
        @yield('content')
    </main>

    @include('nit::partials.footer')
    @include('nit::partials.client-modals')

    @if(filled($site['whatsapp'] ?? null) && $theme['whatsapp_button'] === 'on')
        <a class="whatsapp-float" href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}?text={{ rawurlencode('Hi Natore IT, I would like to know more about ') }}" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp ({{ $site['whatsapp'] }})">
            <i class="bi bi-whatsapp" aria-hidden="true"></i><span class="whatsapp-float-label">Chat with us</span>
        </a>
    @endif

    @if($theme['mobile_bar'] === 'on')
        <nav class="mobile-bar" aria-label="{{ $tx('Quick actions') }}">
            <a href="tel:{{ preg_replace('/[^+\d]/', '', $site['phone']) }}"><i class="bi {{ $ic('bi-telephone') }}" aria-hidden="true"></i>{{ $tx('Call') }}</a>
            @if(filled($site['whatsapp'] ?? null))
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener"><i class="bi {{ $ic('bi-whatsapp') }}" aria-hidden="true"></i>{{ $tx('WhatsApp') }}</a>
            @endif
            <a class="mobile-bar-main" href="{{ route('nit.site.quote') }}"><i class="bi {{ $ic('bi-file-earmark-text') }}" aria-hidden="true"></i>{{ $tx('Get a quote') }}</a>
        </nav>
    @endif

    @if($theme['back_to_top'] === 'on')
        <button type="button" class="back-to-top" data-back-to-top tabindex="-1" aria-label="Back to top">
            <i class="bi bi-arrow-up" aria-hidden="true"></i>
        </button>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <script src="{{ \ME\Nit\Support\Asset::url('js/nit.js') }}" defer></script>
    @stack('scripts')
    @if(filled($analytics['tawk'] ?? null))
        <script>window.Tawk_API = window.Tawk_API || {}; (function () { var s = document.createElement('script'); s.async = true; s.src = 'https://embed.tawk.to/' + @json($analytics['tawk']); s.charset = 'UTF-8'; s.setAttribute('crossorigin', '*'); document.body.appendChild(s); })();</script>
    @endif
</body>
</html>
