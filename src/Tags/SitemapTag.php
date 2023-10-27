<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\Support\RenderableCollection;
use BytePlatform\Seo\Support\SitemapTag as SupportSitemapTag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SitemapTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        if ( $sitemap = config('seo.sitemap') ) {
            $collection->push(new SupportSitemapTag($sitemap));
        }

        return $collection;
    }
}