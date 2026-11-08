<?php
use Illuminate\Support\Facades\Route;
use ME\Nit\Http\Controllers\Admin;
use ME\Nit\Http\Controllers\SiteController;

// Admin panel for the website content (nit-master: sign-in, roles, sidebar)
Route::group(['prefix' => 'admin', 'as' => 'nit.admin.', 'middleware' => ['web', 'auth', 'authorization']], function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('settings', [Admin\SettingsController::class, 'edit'])->name('settings');
    Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('appearance', [Admin\AppearanceController::class, 'edit'])->name('appearance');
    Route::put('appearance', [Admin\AppearanceController::class, 'update'])->name('appearance.update');
    Route::post('appearance/reset', [Admin\AppearanceController::class, 'reset'])->name('appearance.reset');
    Route::get('page-texts', [Admin\PageTextController::class, 'edit'])->name('page-texts');
    Route::get('page-texts/{page}', [Admin\PageTextController::class, 'edit'])->whereIn('page', array_keys(\ME\Nit\Support\Content::PAGES))->name('page-texts.page');
    // One address per list, so the menu shows which list is open
    Route::get('lists/{list}', [Admin\ListItemController::class, 'index'])->whereIn('list', array_keys(\ME\Nit\Models\ListItem::LISTS))->name('lists.list');
    Route::put('page-texts', [Admin\PageTextController::class, 'update'])->name('page-texts.update');

    $resources = [
        'plan-pages'           => Admin\PlanPageController::class,
        'plans'                => Admin\PlanController::class,
        'packages'             => Admin\WebPackageController::class,
        'ssl'                  => Admin\SslPlanController::class,
        'domains'              => Admin\DomainPriceController::class,
        'portfolio'            => Admin\PortfolioItemController::class,
        'portfolio-categories' => Admin\PortfolioCategoryController::class,
        'team'                 => Admin\TeamMemberController::class,
        'reviews'              => Admin\ReviewController::class,
        'lists'                => Admin\ListItemController::class,
        'software'             => Admin\SoftwarePageController::class,
        'messages'             => Admin\MessageController::class,
    ];
    Route::get('messages/export', [Admin\MessageController::class, 'export'])->name('messages.export');
    foreach ($resources as $uri => $controller) {
        $actions = array_keys($controller::$permissions);
        Route::get($uri, [$controller, 'index'])->name("$uri.index");
        if (in_array('create', $actions, true)) {
            Route::get("$uri/create", [$controller, 'create'])->name("$uri.create");
            Route::post($uri, [$controller, 'store'])->name("$uri.store");
        }
        Route::get("$uri/{id}/edit", [$controller, 'edit'])->whereNumber('id')->name("$uri.edit");
        Route::put("$uri/{id}", [$controller, 'update'])->whereNumber('id')->name("$uri.update");
        if (in_array('destroy', $actions, true)) {
            Route::delete("$uri/{id}", [$controller, 'destroy'])->whereNumber('id')->name("$uri.destroy");
        }
    }
});

// Package assets fallback (used when nit-assets has not been published)
Route::get('vendor/nit/{path}', [SiteController::class, 'asset'])
    ->where('path', '(css|js|img)/[A-Za-z0-9._-]+')
    ->name('nit.asset');

// Public website (URLs mirror natoreit.com)
Route::group(['prefix' => config('nit.site_prefix', ''), 'as' => 'nit.site.', 'middleware' => ['web']], function () {
    Route::get('/', [SiteController::class, 'home'])->name('home');
    // An empty whereIn() yields an empty requirement, which Symfony rejects;
    // fall back to a pattern that never matches when no plan pages exist.
    $planSlugs = array_values(array_filter(array_map('strval', array_keys(\ME\Nit\Support\Content::planPages())), 'strlen'));
    Route::get('/{slug}', [SiteController::class, 'plans'])->name('plans')
        ->where('slug', $planSlugs ? implode('|', array_map('preg_quote', $planSlugs)) : '(?!)');
    Route::get('/web-design', [SiteController::class, 'webDesign'])->name('web-design');
    Route::get('/multi-vendor-ecommerce', [SiteController::class, 'multiVendor'])->name('multi-vendor');
    Route::get('/erp-software', [SiteController::class, 'software'])->defaults('key', 'erp')->name('erp');
    Route::get('/custom-web-application', [SiteController::class, 'software'])->defaults('key', 'custom')->name('custom');
    Route::get('/pos-software', [SiteController::class, 'software'])->defaults('key', 'pos')->name('pos');
    Route::get('/crm-software', [SiteController::class, 'software'])->defaults('key', 'crm')->name('crm');
    Route::get('/software-solution', [SiteController::class, 'softwareSolution'])->name('software-solution');
    Route::get('/ssl', [SiteController::class, 'ssl'])->name('ssl');
    Route::get('/domain', [SiteController::class, 'domain'])->name('domain');
    Route::get('/about-us', [SiteController::class, 'about'])->name('about');
    Route::get('/portfolio', [SiteController::class, 'portfolio'])->name('portfolio');
    Route::get('/customer-opinion', [SiteController::class, 'reviews'])->name('reviews');
    Route::get('/contact-us', [SiteController::class, 'contact'])->name('contact');
    Route::get('/coupon', [SiteController::class, 'coupon'])->name('coupon');
    Route::post('/contact-us', [SiteController::class, 'sendMessage'])->middleware('throttle:5,1')->name('contact.send');
    Route::get('/get-quote', [SiteController::class, 'quote'])->name('quote');
    Route::post('/get-quote', [SiteController::class, 'sendQuote'])->middleware('throttle:5,1')->name('quote.send');
    Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
    Route::post('/subscribe', [SiteController::class, 'subscribe'])->middleware('throttle:5,1')->name('subscribe');
    Route::get('/domain/check', [SiteController::class, 'domainCheck'])->middleware('throttle:30,1')->name('domain-check');

    // Old demo URLs
    Route::permanentRedirect('/about', '/about-us');
    Route::permanentRedirect('/contact', '/contact-us');
    Route::permanentRedirect('/hosting', '/shared-hosting');
    Route::permanentRedirect('/pricing', '/web-design');

    Route::fallback([SiteController::class, 'notFound']);
});
