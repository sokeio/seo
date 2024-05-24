<?php

namespace Sokeio\Seo;

use Illuminate\Support\ServiceProvider;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Laravel\WithServiceProvider;
use Sokeio\Seo\Facades\SEO;

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
    public function packageBooted()
    {
        SEO::route();
    }
}
