<?php

namespace Sokeio\Seo\Tags\TwitterCard;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\RenderableCollection;
use Sokeio\Seo\Support\TwitterCardTag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Summary extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $seodata): static
    {
        $collection = new static();

        if ( $seodata->imageMeta ) {
            if ( $seodata->imageMeta->width < 144 ) {
                return $collection;
            }

            if ( $seodata->imageMeta->height < 144 ) {
                return $collection;
            }

            if ( $seodata->imageMeta->width > 4096 ) {
                return $collection;
            }

            if ( $seodata->imageMeta->height > 4096 ) {
                return $collection;
            }
        }

        $collection->push(new TwitterCardTag('card', 'summary'));

        if ( $seodata->image ) {
            $collection->push(new TwitterCardTag('image', $seodata->image));

            if ( $seodata->imageMeta ) {
                $collection
                    ->when($seodata->imageMeta?->width, fn (self $collection): self => $collection->push(new TwitterCardTag('image:width', $seodata->imageMeta->width)))
                    ->when($seodata->imageMeta?->height, fn (self $collection): self => $collection->push(new TwitterCardTag('image:height', $seodata->imageMeta->height)));
            }
        }

        return $collection;
    }
}