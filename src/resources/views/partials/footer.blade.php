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
                    <span class="footer-cta-icon" aria-hidden="true"><i class="bi bi-envelope-paper-heart"></i></span>
                    <div>
                        <h2 id="newsletterTitle">Get hosting deals &amp; domain offers</h2>
                        <p class="mb-0 small">Discounts, renewal reminders and website tips. No spam.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form class="newsletter-form" data-demo-form novalidate aria-labelledby="newsletterTitle">
                        <div class="pill-field">
                            <label class="visually-hidden" for="newsletterEmail">Email address</label>
                            <input class="form-control" type="email" id="newsletterEmail" name="email" placeholder="Your email address" required autocomplete="email">
                            <button class="btn btn-primary" type="submit">Subscribe <i class="bi bi-send icon-shift" aria-hidden="true"></i></button>
                        </div>
                        <div class="invalid-feedback px-3">Enter a valid email address.</div>
                        <div class="form-success mt-3 small" data-form-success role="status" tabindex="-1" hidden>
                            <i class="bi bi-check-circle" aria-hidden="true"></i>
                            <span>Looks good! Newsletter sign-up is not connected yet, so you have not been subscribed.</span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-cta-help">
                <strong><i class="bi bi-headset me-1" aria-hidden="true"></i>Need help?</strong>
                @foreach(array_unique(array_merge($site['phones'], [$site['support']])) as $phone)
                    <a class="phone-chip" href="{{ $tel($phone) }}"><i class="bi bi-telephone" aria-hidden="true"></i>{{ $phone }}</a>
                @endforeach
                <a class="phone-chip is-whatsapp" href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i>WhatsApp<span class="visually-hidden"> (opens in a new tab)</span>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>Hosting packages</h2></summary>
                <ul class="footer-links mb-4">
                    @foreach(collect(\ME\Nit\Support\Content::planPages())->filter(fn ($p, $slug) => str_contains($slug, 'hosting'))->map(fn ($p, $slug) => [$slug, $p['title']])->values() as [$slug, $label])
                        <li><a href="{{ route('nit.site.plans', $slug) }}">{{ $label }}</a></li>
                    @endforeach
                    <li><a href="{{ $links['demo_cpanel'] }}">Demo cPanel</a></li>
                </ul>
                </details>
                <h2>Follow us</h2>
                <div class="d-flex gap-2">
                    <a class="social-link" href="{{ $site['social']['facebook'] }}" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                    <a class="social-link" href="{{ $site['social']['twitter'] }}" aria-label="Twitter"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                    <a class="social-link" href="{{ $site['social']['linkedin'] }}" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
                    <a class="social-link" href="{{ $site['social']['youtube'] }}" aria-label="YouTube"><i class="bi bi-youtube" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>Our services</h2></summary>
                <ul class="footer-links mb-4">
                    <li><a href="{{ route('nit.site.web-design') }}">Web Design</a></li>
                    <li><a href="{{ route('nit.site.plans', 'seo') }}">SEO</a></li>
                </ul>
                </details>
                <details class="footer-group" data-footer-group open>
                    <summary><h2>Resources</h2></summary>
                <ul class="footer-links mb-4">
                    @foreach(\ME\Nit\Support\Content::list('footer_resources') as [$label, $url])
                        <li><a href="{{ $url }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
                </details>
                <h2>We accept</h2>
                <ul class="list-unstyled d-flex flex-wrap gap-2 mb-0">
                    @foreach(\ME\Nit\Support\Content::list('payment_methods') as $method)<li class="payment-chip">{{ $method }}</li>@endforeach
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <details class="footer-group" data-footer-group open>
                    <summary><h2>Company</h2></summary>
                <ul class="footer-links mb-4">
                    <li><a href="{{ route('nit.site.about') }}">About Us</a></li>
                    <li><a href="{{ $links['news'] }}">News / Blogs</a></li>
                    <li><a href="{{ $links['terms'] }}">Refund Policy</a></li>
                </ul>
                </details>
                <details class="footer-group" data-footer-group open>
                    <summary><h2>Support</h2></summary>
                <ul class="footer-links footer-contact">
                    <li><a href="{{ route('nit.site.contact', ['subject' => 'support']) }}">Product Support</a></li>
                    <li><a href="{{ $tel($site['support']) }}">Support Team: <span class="text-nowrap">{{ $site['support'] }}</span></a></li>
                    <li><a href="{{ route('nit.site.contact') }}">Contact Us</a></li>
                    <li><a href="{{ $links['knowledgebase'] }}">Knowledge Base</a></li>
                    <li><a href="{{ route('nit.site.contact', ['subject' => 'sales']) }}">Payment Method</a></li>
                </ul>
                </details>
            </div>
            <div class="col-md-6 col-lg-3">
                <h2>Contact us</h2>
                <address class="footer-contact small mb-4">
                    {{ $site['address'] }}<br>
                    @foreach($site['phones'] as $phone)Phone: <a href="{{ $tel($phone) }}">{{ $phone }}</a><br>@endforeach
                    Support: <a href="{{ $tel($site['support']) }}">{{ $site['support'] }}</a><br>
                    WhatsApp: <a href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener">{{ $site['whatsapp'] }}</a><br>
                    Website: {{ $site['website'] }}<br>
                    Mail: <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
                </address>
                <h2>Location map</h2>
                <div class="skel map-skeleton rounded-2"><iframe class="footer-map" src="{{ $site['map_embed'] }}" title="Natore IT office location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-skeleton></iframe></div>
            </div>
        </div>
    </div>
    <div class="footer-bottom py-3">
        <div class="container d-flex flex-column flex-md-row gap-2 justify-content-between">
            <span>&copy; {{ $site['since'] }}–{{ date('Y') }} {{ $site['name'] }}. All rights reserved.</span>
            <span><a href="{{ $links['terms'] }}">Terms &amp; Service</a> | <a href="{{ $links['terms'] }}">Privacy Policy</a></span>
        </div>
    </div>
</footer>
