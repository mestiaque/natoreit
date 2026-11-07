<?php
namespace ME\Nit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'nit');
        Blade::anonymousComponentPath(__DIR__ . '/resources/views/components', 'nit');

        View::composer('nit::*', function ($view) {
            $view->with('site', Support\Content::settings());
            $view->with('tx', fn (string $text) => Support\Content::text($text));
            $view->with('ic', fn (string $icon) => Support\Content::icon($icon));
            $view->with('theme', Support\Theme::values());
        });
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'nit');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([__DIR__ . '/public' => public_path('/')], 'nit-assets');

        if ($this->app->runningInConsole()) {
            $this->commands([Console\ImportSiteContent::class]);
        }

        // nit-master shows config('sidebar.<module>'); this package's admin menu is "nit"
        Config::set('sidebar.nit', require __DIR__ . '/Config/sidebar.php');
    }

    public function register(): void
    {
        if (file_exists(__DIR__ . '/Config/config.php')) {
            $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'nit');
        }
    }
}