{{-- Domain, Hosting, Web Design & SEO service + website builder. Expects: $siteTypes --}}
    {{-- 4. Services overview + website builder --}}
    <section class="section" aria-labelledby="overviewTitle">
        <div class="container">
            <x-nit::section-heading :eyebrow="$tx('Our services')" :title="$tx('Domain, Hosting, Web Design & SEO Service')" :center="true" id="overviewTitle">
                {{ $tx('One team for everything your business needs online.') }}
            </x-nit::section-heading>
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-6 reveal">
                    <x-nit::mock variant="website" title="Website builder" label="Website builder preview" />
                </div>
                <div class="col-lg-6">
                    <h3 class="h2 mb-3 reveal">{{ $tx('Build your website the easy way') }}</h3>
                    <p class="lead reveal">{{ $tx('Choose a design, add your content and go live — we set up everything behind the scenes.') }}</p>
                    <ul class="check-list my-4 reveal">
                        <li>{{ $tx('Easy to use, no coding needed') }}</li>
                        <li>{{ $tx('Hundreds of customizable designs') }}</li>
                        <li>{{ $tx('Free domain, hosting, database, FTP and business email') }}</li>
                        <li>{{ $tx('Complete website starting at only') }} <strong>$62</strong></li>
                    </ul>
                    <a class="btn btn-primary reveal" href="{{ route('nit.site.web-design') }}">{{ $tx('Read more') }} <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i></a>
                </div>
            </div>
            <ul class="site-types list-unstyled" aria-label="Website types we build" data-stagger="50">
                @foreach($siteTypes as [$icon, $label])
                    <li class="site-type reveal"><i class="bi {{ $icon }}" aria-hidden="true"></i>{{ $label }}</li>
                @endforeach
            </ul>
        </div>
    </section>

