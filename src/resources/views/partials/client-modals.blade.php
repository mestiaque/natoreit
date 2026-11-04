{{-- Client area (login / sign up) — frontend demo only --}}
<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="clientModalTitle">Client area</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills-soft nav-fill mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="client-tab-login" data-bs-toggle="pill" data-bs-target="#client-pane-login" type="button" role="tab" aria-controls="client-pane-login" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="client-tab-signup" data-bs-toggle="pill" data-bs-target="#client-pane-signup" type="button" role="tab" aria-controls="client-pane-signup" aria-selected="false">Sign Up</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="client-pane-login" role="tabpanel" aria-labelledby="client-tab-login" tabindex="0">
                        <form data-demo-form novalidate>
                            <div class="mb-3">
                                <label class="form-label" for="loginEmail">Email</label>
                                <input class="form-control" type="email" id="loginEmail" name="email" required autocomplete="username">
                                <div class="invalid-feedback">Enter a valid email address.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="loginPassword">Password</label>
                                <input class="form-control" type="password" id="loginPassword" name="password" required minlength="6" autocomplete="current-password">
                                <div class="invalid-feedback">Password must be at least 6 characters.</div>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Login <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></button>
                            <div class="form-success mt-3 small" data-form-success role="status" tabindex="-1" hidden>
                                <i class="bi bi-info-circle" aria-hidden="true"></i>
                                <span>Details look valid, but this is a demo website — there is no client area to sign in to yet.</span>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="client-pane-signup" role="tabpanel" aria-labelledby="client-tab-signup" tabindex="0">
                        <form data-demo-form novalidate>
                            <div class="mb-3">
                                <label class="form-label" for="signupName">Full name</label>
                                <input class="form-control" id="signupName" name="name" required minlength="2" autocomplete="name">
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="signupEmail">Email</label>
                                <input class="form-control" type="email" id="signupEmail" name="email" required autocomplete="email">
                                <div class="invalid-feedback">Enter a valid email address.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="signupPassword">Password</label>
                                <input class="form-control" type="password" id="signupPassword" name="password" required minlength="8" autocomplete="new-password" aria-describedby="signupPasswordHint">
                                <div class="form-hint" id="signupPasswordHint">At least 8 characters.</div>
                                <div class="invalid-feedback">Password must be at least 8 characters.</div>
                            </div>
                            <button class="btn btn-primary w-100" type="submit">Create account <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></button>
                            <div class="form-success mt-3 small" data-form-success role="status" tabindex="-1" hidden>
                                <i class="bi bi-check-circle" aria-hidden="true"></i>
                                <span>Thanks<span data-success-name></span>! Your details passed validation. <strong>This is a demo — no account was created.</strong></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Coupon (admin › Website settings) --}}
@php $coupon = $site['coupon'] ?? []; @endphp
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="couponModalTitle">Current offer</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <span class="icon-box icon-box-lg mb-3" aria-hidden="true"><i class="bi bi-ticket-perforated"></i></span>
                <p class="mb-3">Get <strong>{{ $coupon['headline'] ?? '' }}</strong> {{ $coupon['product'] ?? '' }}. Use this code at checkout.</p>
                <div class="coupon-code mx-auto">
                    <label class="visually-hidden" for="couponCode">Coupon code</label>
                    <input class="form-control text-center" id="couponCode" value="{{ $coupon['code'] ?? '' }}" readonly>
                    <button type="button" class="btn btn-primary" data-copy="#couponCode">
                        <i class="bi bi-clipboard" aria-hidden="true"></i><span data-copy-label>Copy</span>
                    </button>
                </div>
                <p class="small text-muted mt-2 mb-0" data-copy-status role="status" aria-live="polite"></p>
                <a class="btn btn-primary mt-3" href="{{ $coupon['order_url'] ?? '#' }}">{{ $coupon['order_label'] ?? 'Order now' }} <i class="bi bi-arrow-right icon-shift" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</div>
