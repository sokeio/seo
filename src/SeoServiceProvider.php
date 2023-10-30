<?php

namespace BytePlatform\Seo;

use Illuminate\Support\ServiceProvider;
use BytePlatform\Laravel\ServicePackage;
use BytePlatform\Laravel\WithServiceProvider;
use BytePlatform\Seo\Facades\SEO;
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
        SEO::Route();
    }
}
