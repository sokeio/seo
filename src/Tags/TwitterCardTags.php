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

    public static function initialize(SEOData $SEOData): ?static
    {
        $collection = new static();

        // No generic image that spans multiple pages
        if ( $SEOData->image && $SEOData->image !== secure_url(config('seo.image.fallback')) ) {
            if ( $SEOData->imageMeta?->width - $SEOData->imageMeta?->height - 20 < 0 ) {
                $collection->push(Summary::initialize($SEOData));
            }

            if ( $SEOData->imageMeta?->width - 2 * $SEOData->imageMeta?->height - 20 < 0 ) {
                $collection->push(SummaryLargeImage::initialize($SEOData));
            }
        } else {
            $collection->push(new TwitterCardTag('card', 'summary'));
        }

        if ( $SEOData->title ) {
            $collection->push(new TwitterCardTag('title', $SEOData->title));
        }

        if ( $SEOData->description ) {
            $collection->push(new TwitterCardTag('description', $SEOData->description));
        }

        if ( $SEOData->twitter_username && $SEOData->twitter_username !== '@' ) {
            $collection->push(new TwitterCardTag('site', $SEOData->twitter_username));
        }

        return $collection;
    }
}