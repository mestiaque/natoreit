@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('contact'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@php
    $tel = fn ($p) => 'tel:' . preg_replace('/[^+\d]/', '', $p);
    $subjects = \ME\Nit\Models\Message::SUBJECTS;
    $subject = request()->query('subject');
    $prefill = match (true) {
        is_string(request()->query('domain')) => 'I would like to register the domain ' . request()->query('domain') . '.',
        is_string(request()->query('product')) => 'I am interested in ' . request()->query('product') . '.',
        default => '',
    };
@endphp

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="nit-card nit-card-body p-lg-4 reveal">
                        <h2 class="h5 mb-1">{{ $tx('Support team:') }} <a href="{{ $tel($site['support']) }}">{{ $site['support'] }}</a> &amp; <a href="{{ $tel($site['phone']) }}">{{ $site['phone'] }}</a></h2>
                        <p class="small mb-3">{{ $tx('Please be patient while waiting for a response. Fields marked * are required.') }}</p>

                        <form class="needs-validation" method="POST" action="{{ route('nit.site.contact.send') }}" data-ajax-form novalidate>
                            @csrf
                            <div class="visually-hidden" aria-hidden="true"><label>{{ $tx('Website') }} <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="contactName">{{ $tx('Full name *') }}</label>
                                    <input class="form-control" id="contactName" name="name" value="{{ old('name') }}" required minlength="2" autocomplete="name">
                                    <div class="invalid-feedback">{{ $tx('Please enter your name.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactEmail">{{ $tx('Email *') }}</label>
                                    <input class="form-control" type="email" id="contactEmail" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    <div class="invalid-feedback">{{ $tx('Enter a valid email address.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactPhone">{{ $tx('Mobile number') }}</label>
                                    <input class="form-control" type="tel" id="contactPhone" name="phone" pattern="(\+?88)?01[3-9]\d{8}" placeholder="01XXXXXXXXX" autocomplete="tel">
                                    <div class="invalid-feedback">{{ $tx('Enter a valid 11-digit mobile number.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="contactSubject">{{ $tx('Subject *') }}</label>
                                    <select class="form-select" id="contactSubject" name="subject" required>
                                        <option value="">{{ $tx('Choose a subject…') }}</option>
                                        @foreach($subjects as $key => $label)
                                            <option value="{{ $key }}" @selected($subject === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ $tx('Please choose a subject.') }}</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="contactMessage">{{ $tx('Message *') }}</label>
                                    <textarea class="form-control" id="contactMessage" name="message" rows="5" required minlength="10" maxlength="2000">{{ $prefill }}</textarea>
                                    <div class="invalid-feedback">{{ $tx('Please write a short message (at least 10 characters).') }}</div>
                                </div>
                            </div>
                            <div class="form-success mt-3" data-form-success role="status" tabindex="-1" @if(! session('form_success')) hidden @endif>
                                <i class="bi {{ $ic('bi-check-circle') }}" aria-hidden="true"></i>
                                <span data-form-message>{{ session('form_success') }}</span>
                            </div>
                            <div class="alert alert-danger mt-3 mb-0 small" data-form-error role="alert" @if(! $errors->any()) hidden @endif>{{ $errors->first() }}</div>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-primary" type="submit" data-loading-text="{{ $tx('Sending…') }}">{{ $tx('Submit') }} <i class="bi {{ $ic('bi-send') }} icon-shift" aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="nit-card nit-card-body reveal">
                        <h2 class="h5"><i class="bi {{ $ic('bi-building') }} me-1 text-primary" aria-hidden="true"></i>{{ $tx('Dhaka Office') }}</h2>
                        <address class="contact-list mb-3">
                            <span><i class="bi {{ $ic('bi-geo-alt') }}" aria-hidden="true"></i>{{ $site['address'] }}</span>
                            @foreach($site['phones'] as $phone)<a href="{{ $tel($phone) }}"><i class="bi {{ $ic('bi-telephone') }}" aria-hidden="true"></i>{{ $phone }}</a>@endforeach
                            <a href="{{ $tel($site['support']) }}"><i class="bi {{ $ic('bi-headset') }}" aria-hidden="true"></i>Support: {{ $site['support'] }}</a>
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $site['whatsapp']) }}" target="_blank" rel="noopener"><i class="bi {{ $ic('bi-whatsapp') }}" aria-hidden="true"></i>WhatsApp: {{ $site['whatsapp'] }}</a>
                            <a href="mailto:{{ $site['email'] }}"><i class="bi {{ $ic('bi-envelope') }}" aria-hidden="true"></i>{{ $site['email'] }}</a>
                            <span><i class="bi {{ $ic('bi-globe') }}" aria-hidden="true"></i>{{ $site['website'] }}</span>
                        </address>
                        <div class="skel map-skeleton rounded-3" style="height: 220px"><iframe class="footer-map h-100" src="{{ $site['map_embed'] }}" title="Natore-IT Dhaka office location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-skeleton></iframe></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
