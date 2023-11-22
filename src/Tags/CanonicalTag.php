<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\LinkTag;
use Sokeio\Seo\Support\RenderableCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class CanonicalTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        if (config('seo.canonical_link')) {
            $collection->push(new LinkTag('canonical', $SEOData->canonical_url ?? $SEOData->url));
        }

        return $collection;
    }
}
