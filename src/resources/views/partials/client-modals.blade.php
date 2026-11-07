{{-- Coupon (admin › Website settings) --}}
@php $coupon = $site['coupon'] ?? []; @endphp
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="couponModalTitle">{{ $tx('Current offer') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <span class="icon-box icon-box-lg mb-3" aria-hidden="true"><i class="bi {{ $ic('bi-ticket-perforated') }}"></i></span>
                <p class="mb-3">{{ $tx('Get') }} <strong>{{ $coupon['headline'] ?? '' }}</strong> {{ $coupon['product'] ?? '' }}. Use this code at checkout.</p>
                <div class="coupon-code mx-auto">
                    <label class="visually-hidden" for="couponCode">{{ $tx('Coupon code') }}</label>
                    <input class="form-control text-center" id="couponCode" value="{{ $coupon['code'] ?? '' }}" readonly>
                    <button type="button" class="btn btn-primary" data-copy="#couponCode">
                        <i class="bi {{ $ic('bi-clipboard') }}" aria-hidden="true"></i><span data-copy-label>{{ $tx('Copy') }}</span>
                    </button>
                </div>
                <p class="small text-muted mt-2 mb-0" data-copy-status role="status" aria-live="polite"></p>
                <a class="btn btn-primary mt-3" href="{{ $coupon['order_url'] ?? '#' }}">{{ $coupon['order_label'] ?? 'Order now' }} <i class="bi {{ $ic('bi-arrow-right') }} icon-shift" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</div>
