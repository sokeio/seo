<?php

namespace BytePlatform\Seo;

use Illuminate\Support\ServiceProvider;
use BytePlatform\Laravel\ServicePackage;
use BytePlatform\Laravel\WithServiceProvider;
use BytePlatform\Seo\Facades\Sitemap;
use Illuminate\Support\Facades\Route;

class SeoServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('seo')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function extending()
    {
    }
    public function packageRegistered()
    {
        $this->extending();
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
        }
    }
    public function packageBooted()
    {
        $this->bootGate();
        if (config('seo.sitemap.route_enabled')) {
            Route::group(['middleware' => 'web'], function () {
                Sitemap::Route();
            });
        }
    }
}
