# Nit Package

Auto-generated Laravel package by ME Utility.

## Installation

```bash
composer require mestiaque/nit
```

## Usage

After installing the package, the service provider will be auto-discovered by Laravel.

### Routes

- Web: `/nit`
- API: `/api/nit`

### Views

```php
view('nit::index');
```

### Translations

```php
trans('nit::message.welcome');
```

### Config

Publish the config file:

```bash
php artisan vendor:publish --tag=nit-config
```

## Public Website

The package ships the public Natore IT website (Bootstrap 5 + Blade). Pages, URLs and content mirror natoreit.com.

| Route | Page |
| --- | --- |
| `/` | Home |
| `/shared-hosting`, `/wordpress-hosting`, `/e-commerce-hosting`, `/reseller-hosting`, `/master-reseller-hosting`, `/alpha-master-reseller-hosting`, `/vps`, `/seo` | Plan pages (one template, data in `Support/Catalog::planPages()`) |
| `/web-design`, `/multi-vendor-ecommerce`, `/pos-software`, `/crm-software`, `/software-solution` | Web design & software |
| `/ssl`, `/domain` | SSL certificates, domain search & reseller links |
| `/about-us`, `/portfolio`, `/customer-opinion`, `/contact-us`, `/coupon` | Company pages |

"Buy now / Order" buttons link to the WHMCS cart on billing.natoreit.com. Blog links to blog.natoreit.com.
Portfolio, team, reviews and software feature lists live in `src/Support/content/*.php`.

- Set `NIT_SITE_PREFIX` (config `nit.site_prefix`) to serve the site under a prefix.
- Contact details live in `config('nit.site')`; home page content in `src/Support/SiteData.php`.
- CSS/JS (`src/public/vendor/nit`) are served by a fallback route; optionally publish them
  with `php artisan vendor:publish --tag=nit-assets` (re-publish after changing them).
- Interaction tokens (durations, easing, elevation, movement) are CSS variables at the top of `nit.css`.

### Domain search

The domain search calls `GET /domain/check?domain=name.com` (throttled to 30/min, cached 5 min),
which asks the WHMCS billing system for availability, price and suggestions.
"Add to cart" sends the visitor to the WHMCS cart.

| `.env` | Default | Purpose |
| --- | --- | --- |
| `NIT_DOMAIN_DRIVER` | `whmcs-cart` | `whmcs-cart` (WHMCS's own `/domain/check`, no credentials), `whmcs-api` (official API), `demo` (sample data) |
| `NIT_BILLING_URL` | `https://billing.natoreit.com` | WHMCS base URL |
| `NIT_WHMCS_API_IDENTIFIER` / `NIT_WHMCS_API_SECRET` | — | Required for `whmcs-api` (allow the server IP in WHMCS) |
| `NIT_WHMCS_CURRENCY_ID` | `1` | Currency for `whmcs-api` prices |

`whmcs-cart` relies on an internal WHMCS endpoint and may break after a WHMCS update or if
reCAPTCHA is enabled for the domain checker; switch to `whmcs-api` for a supported integration.
