{{-- Live domain search (availability, price and suggestions from the WHMCS billing system). One per page. --}}
<form class="domain-search enter enter-3" data-domain-search="#domainResults" data-endpoint="{{ route('nit.site.domain-check') }}"
      novalidate role="search" aria-label="Domain search">
    <div class="inline-form d-flex align-items-start gap-2">
        <div class="flex-grow-1" style="min-width: 0">
            <label class="visually-hidden" for="domainInput">Domain name</label>
            <input class="form-control form-control-lg" id="domainInput" name="domain" placeholder="e.g. yourbusiness.com" required maxlength="80"
                   autocomplete="off" autocapitalize="off" spellcheck="false" aria-describedby="domainHint domainError">
        </div>
        <button class="btn btn-primary btn-lg" type="submit" aria-label="Search domain">
            <span class="spinner-border spinner-border-sm btn-spinner" aria-hidden="true"></span>
            <i class="bi bi-search btn-label-icon" aria-hidden="true"></i><span class="d-none d-sm-inline" aria-hidden="true">Search</span>
        </button>
    </div>
</form>
<p class="domain-error" id="domainError" data-domain-input-error role="alert">Enter a name using letters, numbers and hyphens (e.g. natoreshop.com).</p>
<p class="text-center small text-muted mt-3 mb-0 enter enter-4" id="domainHint">
    <i class="bi bi-lightning-charge me-1" aria-hidden="true"></i>Live availability and prices from our billing system.
</p>
<div class="domain-results" id="domainResults" aria-live="polite" hidden>
    <div class="alert alert-warning small" data-domain-alert role="alert" hidden></div>
    <div data-domain-primary></div>
    <div class="domain-group" data-domain-group="spotlight" hidden>
        <h2 class="domain-group-title">More extensions</h2>
        <div data-domain-list></div>
    </div>
    <div class="domain-group" data-domain-group="suggestions" hidden>
        <h2 class="domain-group-title">Suggested domains</h2>
        <div data-domain-list></div>
    </div>
</div>
