<?php

namespace Sokeio\Seo;

use Sokeio\Seo\Facades\Sitemap;
use Sokeio\Seo\SubmitManager;
use Illuminate\Support\Facades\Route;
use Closure;
use Illuminate\Database\Eloquent\Model;

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

    protected array $seodataTransformers = [];
    protected Model|SEOData|null $source = null;

    public function dataTransformer(Closure $transformer): static
    {
        $this->seodataTransformers[] = $transformer;
        return $this;
    }

    public function tagTransformer(Closure $transformer): static
    {
        $this->tagTransformers[] = $transformer;

        return $this;
    }
    public function for(Model|SEOData|null $source = null)
    {
        $this->source = $source;
    }
    public function getTagTransformers(): array
    {
        return $this->tagTransformers;
    }

    public function getDataTransformers(): array
    {
        return $this->seodataTransformers;
    }
    public function getSource()
    {
        return $this->source;
    }
    public function sendSitemap($sitemap, $engines = [])
    {
        return SubmitManager::sendSitemap($sitemap, $engines);
    }
    public function indexNow(string| array $url, $host, $engines = [])
    {
        return SubmitManager::sendUrl($url, $host, $engines);
    }
    private function routeRobots()
    {
        if (config('seo.robots.route_enabled')) {
            Route::group(['middleware' => 'web'], function () {
                Route::get('robots.txt', function () {
                    $robots = new Robots();
                    Sitemap::clear();
                    doAction('SEO_SITEMAP_INDEX');
                    foreach (Sitemap::getSitemaps() as $item) {
                        $robots->sitemap($item->getLocation());
                    }
                    $robots->bot('*', ['disallow' => ['']]);
                    $robots = applyFilters('SEO_ROBOT_TXT', $robots);
                    return response($robots, 200)
                        ->header('Content-Type', 'text/plain');
                });
            });
        }
    }
    private function routeSitemap()
    {
        if (config('seo.sitemap.route_enabled')) {
            Route::group(['middleware' => 'web'], function () {
                Route::get('sitemap.xml', function () {
                    return redirect(url('sitemap_index.xml'));
                });
                Route::get('sitemap_index.xml', function () {
                    if (!Sitemap::hasCachedView()) {
                        Sitemap::clear();
                        doAction('SEO_SITEMAP_INDEX');
                    }
                    doAction('SEO_SITEMAP');
                    return Sitemap::renderSitemapIndex();
                })->name('sitemap_index');

                Route::get('sitemap_{sitemap}_{page?}.xml', function ($sitemap, $page = 0) {
                    if (!Sitemap::hasCachedView()) {
                        Sitemap::clear();
                        doAction('SEO_SITEMAP_PAGE_' . str($sitemap)->upper(), $page);
                    }
                    doAction('SEO_SITEMAP');
                    return Sitemap::renderSitemap();
                })->name('sitemap_page');
                Route::get('sitemap_{sitemap}.xml', function ($sitemap, $page = 0) {
                    if (!Sitemap::hasCachedView()) {
                        Sitemap::clear();
                        doAction('SEO_SITEMAP_' . str($sitemap)->upper(), $sitemap);
                    }
                    doAction('SEO_SITEMAP');
                    return Sitemap::renderSitemapIndex();
                })->name('sitemap_type');
            });
        }
    }
    public function route()
    {
        $this->routeRobots();
        $this->routeSitemap();
    }
}
