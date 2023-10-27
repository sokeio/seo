<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\Support\MetaTag;
use BytePlatform\Seo\Support\RenderableCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class RobotsTag extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        $robotsContent = config('seo.robots.default');

        if (!config('seo.robots.force_default')) {
            $robotsContent = $SEOData?->robots ?? $robotsContent;
        }

        $collection->push(new MetaTag('robots', $robotsContent));

        return $collection;
    }
}
