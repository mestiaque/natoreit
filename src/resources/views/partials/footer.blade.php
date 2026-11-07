@php
    $tel = fn ($phone) => 'tel:' . preg_replace('/[^+\d]/', '', $phone);
    $links = $site['links'];
@endphp
<footer class="site-footer pt-4">
    <div class="container pb-4">
        {{-- Newsletter + help --}}
        <div class="footer-cta">
            <div class="row g-3 g-lg-4 align-items-center">
                <div class="col-lg-5 d-flex gap-3 align-items-center">
                    <span class="footer-cta-icon" aria-hidden="true"><i class="bi {{ $ic('bi-envelope-paper-heart') }}"></i></span>
                    <div>
                        <h2 id="newsletterTitle">{{ $tx('Get hosting deals & domain offers') }}</h2>
                        <p class="mb-0 small">{{ $tx('Discounts, renewal reminders and website tips. No spam.') }}</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form class="newsletter-form" method="POST" action="{{ route('nit.site.subscribe') }}" data-ajax-form novalidate aria-labelledby="newsletterTitle">
                        @csrf
                        <div class="visually-hidden" aria-hidden="true"><label>{{ $tx('Website') }} <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                        <div class="pill-field">
                            <label class="visually-hidden" for="newsletterEmail">{{ $tx('Email address') }}</label>
                            <input class="form-control" type="email" id="newsletterEmail" name="email" placeholder="{{ $tx('Your email address') }}" required autocomplete="email">
                            <button class="btn btn-primary" type="submit">{{ $tx('Subscribe') }} <i class="bi {{ $ic('bi-send') }} icon-shift" aria-hidden="true"></i></button>
                        </div>
                        <div class="invalid-feedback px-3">{{ $tx('Enter a valid email address.') }}</div>
                        <div class="form-success mt-3 small" data-form-success role="status" tabindex="-1" hidden>
                            <i class="bi {{ $ic('bi-check-circle') }}" aria-hidden="true"></i>
                            <span data-form-message></span>
                        </div>
                        <div class="small mt-2 px-3 text-warning" data-form-error role="alert" hidden></div>
                    </form>
                </div>
            </div>
            <div class="footer-cta-help">
                <strong><i class="bi {{ $ic('bi-headset') }} me-1" aria-hidden="true"></i>{{ $tx('Need help?') }}</strong>
                @foreach(array_unique(array_merge($site['phones'], [$site['support']])) as $phone)
                    <a class="phone-chip" href="{{ $tel($phone) }}"><i class="bi {{ $ic('bi-telephone') }}" aria-hidden="true"></i>{{ $phone }}</a>
                @endforeach
                <a class="phone-chip is-whatsapp" href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener">
                    <i class="bi {{ $ic('bi-whatsapp') }}" aria-hidden="true"></i>{{ $tx('WhatsApp') }}<span class="visually-hidden"> (opens in a new tab)</span>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>{{ $tx('Hosting packages') }}</h2></summary>
                <ul class="footer-links mb-4">
                    @foreach(collect(\ME\Nit\Support\Content::planPages())->filter(fn ($p, $slug) => str_contains($slug, 'hosting'))->map(fn ($p, $slug) => [$slug, $p['title']])->values() as [$slug, $label])
                        <li><a href="{{ route('nit.site.plans', $slug) }}">{{ $label }}</a></li>
                    @endforeach
                    <li><a href="{{ $links['demo_cpanel'] }}">{{ $tx('Demo cPanel') }}</a></li>
                </ul>
                </details>
                @if(collect($site['social'] ?? [])->filter(fn ($url) => $url && $url !== '#')->isNotEmpty())
                    <h2>{{ $tx('Follow us') }}</h2>
                    @include('nit::partials.social', ['class' => 'list-unstyled d-flex gap-2 mb-0', 'itemClass' => 'social-link'])
                @endif
            </div>
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>{{ $tx('Our services') }}</h2></summary>
                <ul class="footer-links mb-4">
                    <li><a href="{{ route('nit.site.web-design') }}">{{ $tx('Web Design') }}</a></li>
                    <li><a href="{{ route('nit.site.erp') }}">{{ $tx('ERP Software') }}</a></li>
                    <li><a href="{{ route('nit.site.pos') }}">{{ $tx('POS Software') }}</a></li>
                    <li><a href="{{ route('nit.site.custom') }}">{{ $tx('Custom Web Application') }}</a></li>
                    <li><a href="{{ route('nit.site.plans', 'seo') }}">SEO</a></li>
                </ul>
                </details>
                <details class="footer-group" data-footer-group open>
                    <summary><h2>{{ $tx('Resources') }}</h2></summary>
                <ul class="footer-links mb-4">
                    @foreach(\ME\Nit\Support\Content::list('footer_resources') as [$label, $url])
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
                </details>
                <h2>{{ $tx('We accept') }}</h2>
                <ul class="list-unstyled d-flex flex-wrap gap-2 mb-0">
                    @foreach(\ME\Nit\Support\Content::list('payment_methods') as $method)<li class="payment-chip">{{ $method }}</li>@endforeach
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>{{ $tx('Company') }}</h2></summary>
                <ul class="footer-links mb-4">
                    <li><a href="{{ route('nit.site.about') }}">{{ $tx('About Us') }}</a></li>
                    <li><a href="{{ $links['news'] }}">{{ $tx('News / Blogs') }}</a></li>
                    <li><a href="{{ $links['refund'] ?? $links['terms'] }}">{{ $tx('Refund Policy') }}</a></li>
                </ul>
                </details>
                <details class="footer-group" data-footer-group open>
                    <summary><h2>{{ $tx('Support') }}</h2></summary>
                <ul class="footer-links footer-contact">
                    <li><a href="{{ route('nit.site.contact', ['subject' => 'support']) }}">{{ $tx('Product Support') }}</a></li>
                    <li><a href="{{ $tel($site['support']) }}">{{ $tx('Support Team:') }} <span class="text-nowrap">{{ $site['support'] }}</span></a></li>
                    <li><a href="{{ route('nit.site.contact') }}">{{ $tx('Contact Us') }}</a></li>
                    <li><a href="{{ $links['knowledgebase'] }}">{{ $tx('Knowledge Base') }}</a></li>
                    <li><a href="{{ route('nit.site.contact', ['subject' => 'sales']) }}">{{ $tx('Payment Method') }}</a></li>
                </ul>
                </details>
            </div>
            <div class="col-md-6 col-lg-3">
                <h2>{{ $tx('Contact us') }}</h2>
                <address class="footer-contact small mb-4">
                    {{ $site['address'] }}<br>
                    @foreach($site['phones'] as $phone)Phone: <a href="{{ $tel($phone) }}">{{ $phone }}</a><br>@endforeach
                    Support: <a href="{{ $tel($site['support']) }}">{{ $site['support'] }}</a><br>
                    {{ $tx('WhatsApp:') }} <a href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener">{{ $site['whatsapp'] }}</a><br>
                    Website: {{ $site['website'] }}<br>
                    {{ $tx('Mail:') }} <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
                </address>
                <h2>{{ $tx('Location map') }}</h2>
                <div class="skel map-skeleton rounded-2"><iframe class="footer-map" src="{{ $site['map_embed'] }}" title="Natore IT office location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-skeleton></iframe></div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div class="container d-flex flex-column flex-md-row gap-2 justify-content-between">
            <span>&copy; {{ $site['since'] }}–{{ date('Y') }} {{ $site['name'] }}. All rights reserved.</span>
            <span><a href="{{ $links['terms'] }}">{{ $tx('Terms & Service') }}</a> | <a href="{{ $links['privacy'] ?? $links['terms'] }}">{{ $tx('Privacy Policy') }}</a></span>
        </div>
    </div>
</footer>
