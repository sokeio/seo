<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\RenderableCollection;
use Sokeio\Seo\Support\TwitterCardTag;
use Sokeio\Seo\Tags\TwitterCard\Summary;
use Sokeio\Seo\Tags\TwitterCard\SummaryLargeImage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class TwitterCardTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $seodata): ?static
    {
        $collection = new static();

        // No generic image that spans multiple pages
        if ( $seodata->image && $seodata->image !== secure_url(config('seo.image.fallback')) ) {
            if ( $seodata->imageMeta?->width - $seodata->imageMeta?->height - 20 < 0 ) {
                $collection->push(Summary::initialize($seodata));
            }

            if ( $seodata->imageMeta?->width - 2 * $seodata->imageMeta?->height - 20 < 0 ) {
                $collection->push(SummaryLargeImage::initialize($seodata));
            }
        } else {
            $collection->push(new TwitterCardTag('card', 'summary'));
        }

        if ( $seodata->title ) {
            $collection->push(new TwitterCardTag('title', $seodata->title));
        }

        if ( $seodata->description ) {
            $collection->push(new TwitterCardTag('description', $seodata->description));
        }

        if ( $seodata->twitter_username && $seodata->twitter_username !== '@' ) {
            $collection->push(new TwitterCardTag('site', $seodata->twitter_username));
        }

        return $collection;
    }
}