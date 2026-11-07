<section class="section-sm">
    <div class="container">
        <div class="cta-band reveal">
            <div class="row align-items-center g-4 position-relative" style="z-index: 1">
                <div class="col-lg-7">
                    <h2 class="h2 mb-2">{{ $ctaHeading ?? $tx('Ready to take your business online?') }}</h2>
                    <p class="mb-0">{{ $ctaText ?? $tx('Domain, hosting, websites and software from one team, with 24/7 support whenever you need it.') }}</p>
                </div>
                <div class="col-lg-5 d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a class="btn btn-light btn-lg" href="{{ $ctaUrl ?? 'https://billing.natoreit.com/cart.php' }}">
                        {{ $ctaLabel ?? $tx('View all packages') }} <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-glass btn-lg" href="{{ route('nit.site.contact') }}">{{ $tx('Contact us') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
