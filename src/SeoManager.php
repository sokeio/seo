<?php

namespace BytePlatform\Seo;

use BytePlatform\Seo\Facades\Sitemap;
use BytePlatform\Seo\Submits\SubmitManager;
use Illuminate\Support\Facades\Route;
use Closure;

class SEOManager
{
    /**
     * Changes every time it is accessed.
     *
     * @var string
     */
    public const SITEMAP_ALWAYS = 'always';

    /**
     * Changes hourly.
     *
     * @var string
     */
    public const SITEMAP_HOURLY = 'hourly';

    /**
     * Changes daily.
     *
     * @var string
     */
    public const SITEMAP_DAILY = 'daily';

    /**
     * Changes weekly.
     *
     * @var string
     */
    public const SITEMAP_WEEKLY = 'weekly';

    /**
     * Changes monthly.
     *
     * @var string
     */
    public const SITEMAP_MONTHLY = 'monthly';

    /**
     * Changes yearly.
     *
     * @var string
     */
    public const SITEMAP_YEARLY = 'yearly';

    /**
     * Never changes, archived content.
     *
     * @var string
     */
    public const SITEMAP_NEVER = 'never';

    protected array $tagTransformers = [];

    protected array $SEODataTransformers = [];

    public function SEODataTransformer(Closure $transformer): static
    {
        $this->SEODataTransformers[] = $transformer;

        return $this;
    }

    public function tagTransformer(Closure $transformer): static
    {
        $this->tagTransformers[] = $transformer;

        return $this;
    }

    public function getTagTransformers(): array
    {
        return $this->tagTransformers;
    }

    public function getSEODataTransformers(): array
    {
        return $this->SEODataTransformers;
    }
    public function SendSitemap($sitemap, $engines = [])
    {
        return SubmitManager::sendSitemap($sitemap, $engines);
    }
    public function IndexNow(string| array $url, $host, $engines = [])
    {
        return SubmitManager::sendUrl($url, $host, $engines);
    }
    public function Route()
    {
        // add_action('SEO_SITEMAP_INDEX', function () {
        //     Sitemap::addSitemap(route('sitemap_type', ['sitemap' => 'post']));
        //     Sitemap::addSitemap(route('sitemap_type', ['sitemap' => 'tag']));
        //     Sitemap::addSitemap(route('sitemap_type', ['sitemap' => 'page']));
        // });
        // add_action('SEO_SITEMAP_POST', function ($sitemap) {
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 2]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 3]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 4]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 5]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 6]));
        //     Sitemap::addSitemap(route('sitemap_page', ['sitemap' => 'post', 'page' => 7]));
        // });
        // add_action('SEO_SITEMAP_PAGE_POST', function ($page) {
        //     Sitemap::addItem(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        //     Sitemap::addItem(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        //     Sitemap::addItem(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        //     Sitemap::addItem(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        //     Sitemap::addItem(route('sitemap_page', ['sitemap' => 'post', 'page' => 1]));
        // });
        Route::get('robots.txt', function () {
            $Robots = new Robots();
            Sitemap::clear();
            do_action('SEO_SITEMAP_INDEX');
            foreach (Sitemap::getSitemaps() as $item) {
                $Robots->sitemap($item->getLocation());
            }
            $Robots->bot('*', ['disallow' => ['']]);
            return response($Robots, 200)
                ->header('Content-Type', 'text/plain');
        });
        Route::get('sitemap.xml', function () {
            return redirect(url('sitemap_index.xml'));
        });
        Route::get('sitemap_index.xml', function () {
            if (!Sitemap::hasCachedView()) {
                Sitemap::clear();
                do_action('SEO_SITEMAP_INDEX');
            }
            return Sitemap::renderSitemapIndex();
        })->name('sitemap_index');

        Route::get('sitemap_{sitemap}_{page?}.xml', function ($sitemap, $page = 0) {
            if (!Sitemap::hasCachedView()) {
                Sitemap::clear();
                do_action('SEO_SITEMAP_PAGE_' . str($sitemap)->upper(), $page);
            }
            return Sitemap::renderSitemap();
        })->name('sitemap_page');
        Route::get('sitemap_{sitemap}.xml', function ($sitemap, $page = 0) {
            if (!Sitemap::hasCachedView()) {
                Sitemap::clear();
                do_action('SEO_SITEMAP_' . str($sitemap)->upper(), $sitemap);
            }
            return Sitemap::renderSitemapIndex();
        })->name('sitemap_type');
    }
}
