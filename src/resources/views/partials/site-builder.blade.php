{{-- Domain, Hosting, Web Design & SEO service + website builder. Expects: $siteTypes --}}
    {{-- 4. Services overview + website builder --}}
    <section class="section" aria-labelledby="overviewTitle">
        <div class="container">
            <x-nit::section-heading eyebrow="Our services" title="Domain, Hosting, Web Design & SEO Service" :center="true" id="overviewTitle">
                One team for everything your business needs online.
            </x-nit::section-heading>
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-6 reveal">
                    <x-nit::mock variant="website" title="Website builder" label="Website builder preview" />
                </div>
                <div class="col-lg-6">
                    <h3 class="h2 mb-3 reveal">Build your website the easy way</h3>
                    <p class="lead reveal">Choose a design, add your content and go live — we set up everything behind the scenes.</p>
                    <ul class="check-list my-4 reveal">
                        <li>Easy to use, no coding needed</li>
                        <li>Hundreds of customizable designs</li>
                        <li>Free domain, hosting, database, FTP and business email</li>
                        <li>Complete website starting at only <strong>$62</strong></li>
                    </ul>
                    <a class="btn btn-primary reveal" href="{{ route('nit.site.web-design') }}">Read more <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></a>
                </div>
            </div>
            <ul class="site-types list-unstyled" aria-label="Website types we build" data-stagger="50">
                @foreach($siteTypes as [$icon, $label])
                    <li class="site-type reveal"><i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}</li>
                @endforeach
            </ul>
        </div>
    </section>

