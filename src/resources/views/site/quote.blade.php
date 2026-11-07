@extends('nit::layouts.site')
@php $pg = \ME\Nit\Support\Content::page('quote'); @endphp

@section('title', $pg['meta_title'])
@section('description', $pg['description'])

@section('content')
    @include('nit::partials.page-hero', ['eyebrow' => $pg['eyebrow'], 'title' => $pg['title'], 'lead' => $pg['lead']])

    <section class="section">
        <div class="container" style="max-width: 760px">
            <div class="nit-card nit-card-body p-lg-4">
                <form class="needs-validation" method="POST" action="{{ route('nit.site.quote.send') }}" data-ajax-form novalidate>
                    @csrf
                    <div class="visually-hidden" aria-hidden="true"><label>{{ $tx('Website') }} <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="quoteService">{{ $tx('What do you need? *') }}</label>
                            <select class="form-select" id="quoteService" name="subject" required>
                                <option value="">{{ $tx('Choose a service…') }}</option>
                                @foreach($services as $item)
                                    <option value="{{ $item }}" @selected($service === $item)>{{ $item }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">{{ $tx('Please choose a service.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quoteBudget">{{ $tx('Budget') }}</label>
                            <select class="form-select" id="quoteBudget" name="budget">
                                <option value="">{{ $tx('Choose a budget…') }}</option>
                                @foreach($budgets as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quoteName">{{ $tx('Full name *') }}</label>
                            <input class="form-control" id="quoteName" name="name" required minlength="2" autocomplete="name">
                            <div class="invalid-feedback">{{ $tx('Please enter your name.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quotePhone">{{ $tx('Mobile number *') }}</label>
                            <input class="form-control" type="tel" id="quotePhone" name="phone" pattern="(\+?88)?01[3-9]\d{8}" placeholder="01XXXXXXXXX" required autocomplete="tel">
                            <div class="invalid-feedback">{{ $tx('Enter a valid 11-digit mobile number.') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="quoteEmail">{{ $tx('Email *') }}</label>
                            <input class="form-control" type="email" id="quoteEmail" name="email" required autocomplete="email">
                            <div class="invalid-feedback">{{ $tx('Enter a valid email address.') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="quoteMessage">{{ $tx('About your project *') }}</label>
                            <textarea class="form-control" id="quoteMessage" name="message" rows="5" required minlength="10" maxlength="2000"></textarea>
                            <div class="invalid-feedback">{{ $tx('Please write a short message (at least 10 characters).') }}</div>
                        </div>
                    </div>
                    <div class="form-success mt-3" data-form-success role="status" tabindex="-1" hidden>
                        <i class="bi {{ $ic('bi-check-circle') }}" aria-hidden="true"></i>
                        <span data-form-message></span>
                    </div>
                    <div class="alert alert-danger mt-3 mb-0 small" data-form-error role="alert" hidden></div>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary" type="submit" data-loading-text="{{ $tx('Sending…') }}">{{ $tx('Send request') }} <i class="bi {{ $ic('bi-send') }} icon-shift" aria-hidden="true"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
