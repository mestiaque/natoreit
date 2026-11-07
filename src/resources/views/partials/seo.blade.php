{{-- Canonical URL, social sharing cards and structured data for search engines --}}
@php
    $seoTitle = trim(View::yieldContent('title'));
    $seoTitle = $seoTitle !== '' ? $seoTitle . ' · ' . $site['name'] : $site['name'] . ' — Software, Web & Hosting';
    $seoDescription = trim(View::yieldContent('description', $site['tagline']));
    $seoImage = url(\ME\Nit\Support\Asset::url('img/natore-it-logo.png'));
    $sameAs = array_values(array_filter($site['social'] ?? [], fn ($url) => filled($url) && $url !== '#'));
    $business = array_filter([
        '@context'  => 'https://schema.org',
        '@type'     => 'LocalBusiness',
        '@id'       => route('nit.site.home') . '#business',
        'name'      => $site['name'],
        'description' => $site['tagline'] ?? null,
        'url'       => route('nit.site.home'),
        'logo'      => $seoImage,
        'image'     => $seoImage,
        'email'     => $site['email'] ?? null,
        'telephone' => $site['phone'] ?? null,
        'foundingDate' => isset($site['since']) ? (string) $site['since'] : null,
        'address'   => filled($site['address'] ?? null) ? ['@type' => 'PostalAddress', 'streetAddress' => $site['address'], 'addressCountry' => 'BD'] : null,
        'sameAs'    => $sameAs ?: null,
    ]);
@endphp
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $site['name'] }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<script type="application/ld+json">{!! json_encode($business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
