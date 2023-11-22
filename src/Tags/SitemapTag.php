<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\RenderableCollection;
use Sokeio\Seo\Support\SitemapTag as SupportSitemapTag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SitemapTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        if ( $sitemap = config('seo.sitemap.url') ) {
            $collection->push(new SupportSitemapTag($sitemap));
        }

        return $collection;
    }
}