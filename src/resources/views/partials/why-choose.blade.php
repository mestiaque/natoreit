{{-- Why choose us. Expects: $why --}}
    {{-- 3. Why choose us --}}
    <section class="section bg-soft" aria-labelledby="whyTitle">
        <div class="container">
            <x-nit::section-heading :eyebrow="$tx('Why choose us')" :title="$tx('Hosting you can rely on')" :center="true" id="whyTitle">
                {{ $tx('Everything that keeps your website fast, safe and online — included as standard.') }}
            </x-nit::section-heading>
            <div class="row g-4" data-stagger="60">
                @foreach($why as [$icon, $title, $text])
                    <div class="col-md-6 col-lg-4 reveal">
                        <div class="nit-card card-hover nit-card-body d-flex gap-3">
                            <span class="icon-box flex-shrink-0" aria-hidden="true"><i class="bi {{ $icon }}"></i></span>
                            <div><h3 class="h6 mb-1">{{ $title }}</h3><p class="small mb-0">{{ $text }}</p></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

