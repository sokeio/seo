<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\MetaTag;
use Sokeio\Seo\Support\RenderableCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class RobotsTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $seodata = null): static
    {
        $collection = new static();

        $robotsContent = config('seo.robots.default');

        if (!config('seo.robots.force_default')) {
            $robotsContent = $seodata?->robots ?? $robotsContent;
        }
        if ($robotsContent) {
            $collection->push(new MetaTag('robots', $robotsContent));
        }

        return $collection;
    }
}
