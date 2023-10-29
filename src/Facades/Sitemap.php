<?php

namespace BytePlatform\Seo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mix renderSitemapIndex()
 * @method static void addSitemap($location, $lastModified = null)
 * @method static void clearSitemaps()
 * @method static mix renderSitemap()
 * @method static void addItem($location, $lastModified = null, $changeFrequency = null, $priority = null)
 * @method static void addExpiredTag($location, $expired = null)
 * @method static void clearItems()
 * @method static void clear()
 * @method static bool hasCachedView();
 * @method static void Route()
 * 
 */
class Sitemap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Seo\Sitemap\SitemapManager::class;
    }
}
