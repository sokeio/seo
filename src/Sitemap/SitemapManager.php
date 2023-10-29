<?php

namespace BytePlatform\Seo\Sitemap;

use BytePlatform\Seo\Facades\Sitemap;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapManager
{
    /**
     * Collection of sitemaps being used.
     *
     * @var array
     */
    protected $sitemaps = [];

    /**
     * Collection of tags being used in a sitemap.
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Laravel cache repository.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * Laravel request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new sitemap instance.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Cache $cache, Request $request)
    {
        $this->cache = $cache;
        $this->request = $request;
    }

    /**
     * Add new sitemap to the sitemaps index.
     *
     * @param  SitemapItem|string  $location
     * @param  \DateTime|string  $lastModified
     * @return void
     */
    public function addSitemap($location, $lastModified = null)
    {
        $sitemap = $location instanceof SitemapItem ? $location : new SitemapItem($location, $lastModified);

        $this->sitemaps[] = $sitemap;
    }

    /**
     * Retrieve the array of sitemaps.
     *
     * @return array
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     * Render an index of sitemaps.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($cachedView = $this->getCachedView()) {
            return response()->make($cachedView, 200, ['Content-type' => 'text/xml']);
        }

        $sitemapIndex = response()->view('seo::sitemaps', ['__sitemaps' => $this->getSitemaps()], 200, ['Content-type' => 'text/xml']);

        $this->saveCachedView($sitemapIndex);

        return $sitemapIndex;
    }

    /**
     * Render an index of sitemaps.
     *
     * @return \Illuminate\Http\Response
     */
    public function renderSitemapIndex()
    {
        return $this->index();
    }

    /**
     * Add a new sitemap item to the sitemap.
     *
     * @param  Item|string  $location
     * @param  \DateTime|string  $lastModified
     * @param  string  $changeFrequency
     * @param  string  $priority
     * @return Item
     */
    public function addItem($location, $lastModified = null, $changeFrequency = null, $priority = null)
    {
        $tag = $location instanceof Item ? $location : new Item($location, $lastModified, $changeFrequency, $priority);

        $this->tags[] = $tag;

        return $tag;
    }

    /**
     * Add a new expired tag to the sitemap.
     *
     * @param  string  $location
     * @param  \DateTime|string  $expired
     * @return void
     */
    public function addExpiredTag($location, $expired = null)
    {
        $tag = $location instanceof ExpiredItem ? $location : new ExpiredItem($location, $expired);

        $this->tags[] = $tag;
    }

    /**
     * Retrieve the array of tags.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get the formatted sitemap.
     *
     * @return string
     */
    public function xml()
    {
        return $this->render()->getOriginalContent();
    }

    /**
     * Get the formatted sitemap index.
     *
     * @return string
     */
    public function xmlIndex()
    {
        return $this->index()->getOriginalContent();
    }

    /**
     * Render a sitemap.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if ($cachedView = $this->getCachedView()) {
            return response()->make($cachedView, 200, ['Content-type' => 'text/xml']);
        }

        $sitemap = response()->view('seo::sitemap', [
            '__tags' => $this->getTags(),
            '__hasImages' => $this->imagesPresent(),
            '__hasVideos' => $this->videosPresent(),
            '__isMultilingual' => $this->multilingualTagsPresent()
        ], 200, ['Content-type' => 'text/xml']);

        $this->saveCachedView($sitemap);

        return $sitemap;
    }

    /**
     * Render a sitemap.
     *
     * @return \Illuminate\Http\Response
     */
    public function renderSitemap()
    {
        return $this->render();
    }

    /**
     * Clear all the existing sitemaps and items.
     *
     * @return void
     */
    public function clear()
    {
        $this->sitemaps = $this->tags = [];
    }

    /**
     * Remove all the existing sitemaps.
     *
     * @return void
     */
    public function clearSitemaps()
    {
        $this->sitemaps = [];
    }

    /**
     * Remove all the existing Items.
     *
     * @return void
     */
    public function clearItems()
    {
        $this->tags = [];
    }

    /**
     * Check whether the sitemap has a cached view or not.
     *
     * @return bool
     */
    public function hasCachedView()
    {
        if (config('seo.sitemap.cache_enabled')) {
            $key = $this->getCacheKey();

            return $this->cache->has($key);
        }

        return false;
    }

    /**
     * Return whether there are any images present in the sitemap.
     *
     * @return bool
     */
    protected function imagesPresent()
    {
        foreach ($this->tags as $tag) {
            if ($tag->hasImages()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return whether there are any videos present in the sitemap.
     *
     * @return bool
     */
    protected function videosPresent()
    {
        foreach ($this->tags as $tag) {
            if ($tag->hasVideos()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return whether there are any multilingual tags present in the sitemap.
     *
     * @return bool
     */
    protected function multilingualTagsPresent()
    {
        foreach ($this->tags as $tag) {
            if ($tag instanceof MultilingualItem) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check to see whether a view has already been cached for the current
     * route and if so, return it.
     *
     * @return mixed
     */
    protected function getCachedView()
    {
        if ($this->hasCachedView()) {
            $key = $this->getCacheKey();

            return $this->cache->get($key);
        }

        return false;
    }

    /**
     * Save a cached view if caching is enabled.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    protected function saveCachedView(Response $response)
    {
        if (config('seo.sitemap.cache_enabled')) {
            $key = $this->getCacheKey();

            $content = $response->getOriginalContent()->render();

            if (!$this->cache->get($key)) {
                $this->cache->put($key, $content, config('sitemap.cache_length'));
            }
        }
    }

    /**
     * Get the cache key that will be used for saving cached sitemaps
     * to storage.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return 'sitemap_' . Str::slug($this->request->url());
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
