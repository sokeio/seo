<?php

namespace BytePlatform\Seo\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSEODataTransformers()
 * @method static array getTagTransformers()
 * @method static \BytePlatform\Seo\SEOManager SEODataTransformer( Closure $transformer )
 * @method static \BytePlatform\Seo\SEOManager tagTransformer( Closure $transformer )
 * @method static mix SendSitemap($sitemap, $engines = [])
 * @method static mix IndexNow(string| array $url, $host, $engines = [])
 * @method static void Route()
 */
class SEO extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BytePlatform\Seo\SEOManager::class;
    }
}
