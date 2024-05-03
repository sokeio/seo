<?php

namespace Sokeio\Seo\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\Model;
use Sokeio\Seo\SEOData;

/**
 * @method static array getDataTransformers()
 * @method static array getTagTransformers()
 * @method static \Sokeio\Seo\SEOManager dataTransformer( Closure $transformer )
 * @method static \Sokeio\Seo\SEOManager tagTransformer( Closure $transformer )
 * @method static Model|SEOData|null getSource();
 * @method static for(Model|SEOData|null $source = null)
 * @method static mix sendSitemap($sitemap, $engines = [])
 * @method static mix indexNow(string| array $url, $host, $engines = [])
 * @method static void Route()
 */
class SEO extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sokeio\Seo\SEOManager::class;
    }
}
