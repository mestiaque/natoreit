@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('web_design'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    @include('nit::partials.web-packages')
    @include('nit::partials.why-choose')

    <section class="section" aria-labelledby="aboutDesignTitle">
        <div class="container" style="max-width: 880px">
            <x-nit::section-heading eyebrow="Learn" title="Web Design and Development" id="aboutDesignTitle" />
            <div class="article reveal">
                <h3>What is web design?</h3>
                <p>Web design refers to the design of websites that are displayed on the internet. It usually refers to the user experience aspects of website development rather than software development. Web design used to be focused on designing websites for desktop browsers; however, since the mid-2010s, design for mobile and tablet browsers has become ever-increasingly important.</p>
                <p>A web designer works on the appearance, layout, and, in some cases, content of a website. Appearance relates to the colors, font, and images used. Layout refers to how information is structured and categorized. Good web design is easy to use, aesthetically pleasing and suits the user group and brand of the website.</p>
                <p>Two of the most common methods for designing websites that work well both on desktop and mobile are responsive and adaptive design. In responsive design, content moves dynamically depending on screen size; in adaptive design, the website content is fixed in layout sizes that match common screen sizes.</p>
                <h3>What is the use of web design?</h3>
                <p>Web design is a process of conceptualizing, planning, and building a collection of electronic files that determine the layout, colors, text styles, structure, graphics, images, and use of interactive features that deliver pages to your site visitors.</p>
                <h3>Is web design a good career?</h3>
                <p>The easy answer is yes — it still pays well, and many job postings come from the traditional employers of web designers. The technology skills you possess are critical to getting a job.</p>
                <h3>Tips to learn web design</h3>
                <ul class="check-list">
                    <li>Start a website. The best way to start learning web design is to start doing it.</li>
                    <li>Read everything you can.</li>
                    <li>Be an effective communicator.</li>
                    <li>Think in HTML.</li>
                    <li>Play with code.</li>
                    <li>Learn to understand CSS.</li>
                    <li>Apply your design skills to the web.</li>
                </ul>
                <h3>What is the main purpose of a website?</h3>
                <p>The purpose of a website is to turn visitors into prospects. The way to do this is to identify the major user types visiting your site, speak to their needs, and give them a clear action step to take next.</p>
                <p class="mb-0"><strong>Natore-IT is a web design provider company in Bangladesh.</strong></p>
            </div>
        </div>
    </section>

    @include('nit::partials.site-builder')
    @include('nit::partials.cta')
@endsection
