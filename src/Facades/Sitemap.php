<?php

namespace Sokeio\Seo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mix renderSitemapIndex()
 * @method static void addSitemap($location, $lastModified = null)
 * @method static array getSitemaps();
 * @method static void clearSitemaps()
 * @method static mix renderSitemap()
 * @method static void addItem($location, $lastModified = null, $changeFrequency = null, $priority = null)
 * @method static void addExpiredTag($location, $expired = null)
 * @method static void clearItems()
 * @method static void clear()
 * @method static bool hasCachedView();
 *
 */
class Sitemap extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Seo\Sitemap\SitemapManager::class;
    }
}
