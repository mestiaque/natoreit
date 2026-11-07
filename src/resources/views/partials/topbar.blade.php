<div class="topbar">
    <div class="container d-flex justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3 min-w-0">
            @include('nit::partials.social', ['class' => 'topbar-social list-unstyled d-none d-md-flex mb-0', 'itemClass' => null])
            <a class="topbar-link" href="tel:{{ preg_replace('/[^+\d]/', '', $site['phone']) }}"><i class="bi {{ $ic('bi-telephone') }}" aria-hidden="true"></i>{{ $site['phone'] }}</a>
            <a class="topbar-link d-none d-lg-inline-flex" href="mailto:{{ $site['email'] }}"><i class="bi {{ $ic('bi-envelope') }}" aria-hidden="true"></i>{{ $site['email'] }}</a>
        </div>
        <div class="d-flex align-items-center gap-1">
            <a class="topbar-link" href="{{ $site['links']['login'] ?? 'https://billing.natoreit.com/login' }}"><i class="bi {{ $ic('bi-box-arrow-in-right') }}" aria-hidden="true"></i>{{ $tx('Login') }}</a>
            <a class="topbar-link d-none d-sm-inline-flex" href="{{ $site['links']['register'] ?? 'https://billing.natoreit.com/register.php' }}"><i class="bi {{ $ic('bi-person-plus') }}" aria-hidden="true"></i>{{ $tx('Sign Up') }}</a>
            @if($theme['bdt_rate'] > 0)
                <button type="button" class="topbar-link" data-currency-toggle aria-label="{{ $tx('Change currency') }}">৳ / $</button>
            @endif
            <button type="button" class="topbar-link topbar-coupon" data-bs-toggle="modal" data-bs-target="#couponModal"><i class="bi {{ $ic('bi-ticket-perforated') }}" aria-hidden="true"></i>{{ $tx('Coupon') }}</button>
        </div>
    </div>
</div>
