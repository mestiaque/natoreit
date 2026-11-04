<div class="topbar">
    <div class="container d-flex justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3 min-w-0">
            <ul class="topbar-social list-unstyled d-none d-md-flex mb-0" aria-label="Social media">
                <li><a href="{{ $site['social']['facebook'] }}" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a></li>
                <li><a href="{{ $site['social']['twitter'] }}" aria-label="Twitter"><i class="bi bi-twitter-x" aria-hidden="true"></i></a></li>
                <li><a href="{{ $site['social']['linkedin'] }}" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a></li>
                <li><a href="{{ $site['social']['youtube'] }}" aria-label="YouTube"><i class="bi bi-youtube" aria-hidden="true"></i></a></li>
            </ul>
            <a class="topbar-link" href="tel:{{ preg_replace('/[^+\d]/', '', $site['phone']) }}"><i class="bi bi-telephone" aria-hidden="true"></i>{{ $site['phone'] }}</a>
            <a class="topbar-link d-none d-lg-inline-flex" href="mailto:{{ $site['email'] }}"><i class="bi bi-envelope" aria-hidden="true"></i>{{ $site['email'] }}</a>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="topbar-link" data-bs-toggle="modal" data-bs-target="#clientModal" data-client-tab="login"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>Login</button>
            <button type="button" class="topbar-link d-none d-sm-inline-flex" data-bs-toggle="modal" data-bs-target="#clientModal" data-client-tab="signup"><i class="bi bi-person-plus" aria-hidden="true"></i>Sign Up</button>
            <button type="button" class="topbar-link topbar-coupon" data-bs-toggle="modal" data-bs-target="#couponModal"><i class="bi bi-ticket-perforated" aria-hidden="true"></i>Coupon</button>
        </div>
    </div>
</div>
