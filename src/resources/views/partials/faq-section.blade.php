{{-- FAQ section from the "faqs" list, with FAQPage structured data. Optional: $soft (grey background) --}}
@php $faqs = \ME\Nit\Support\Content::list('faqs'); @endphp
@if($faqs)
    <section class="section {{ ($soft ?? false) ? 'bg-soft' : '' }}" aria-labelledby="faqTitle">
        <div class="container" style="max-width: 820px">
            <x-nit::section-heading :eyebrow="$tx('FAQ')" :title="$tx('Frequently asked questions')" :center="true" id="faqTitle" />
            @include('nit::partials.faq', ['faqs' => $faqs, 'id' => 'faq'])
        </div>
    </section>
    @push('head')
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org', '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]], $faqs),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush
@endif
