<?php

namespace BytePlatform\Seo\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;
use BytePlatform\Seo\SEOData;

/**
 * @method static array getSEODataTransformers()
 * @method static array getTagTransformers()
 * @method static \BytePlatform\Seo\SEOManager SEODataTransformer( Closure $transformer )
 * @method static \BytePlatform\Seo\SEOManager tagTransformer( Closure $transformer )
 * @method static Model|SEOData|null getSource();
 * @method static for(Model|SEOData|null $source = null)
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
