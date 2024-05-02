<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\MetaContentTag;
use Sokeio\Seo\Support\OpenGraphTag;
use Sokeio\Seo\Support\RenderableCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class OpenGraphTags extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $seodata): static
    {
        $collection = new static();

        if ( $seodata->title ) {
            $collection->push(new OpenGraphTag('title', $seodata->title));
        }

        if ( $seodata->description ) {
            $collection->push(new OpenGraphTag('description', $seodata->description));
        }

        if ( $seodata->locale ) {
            $collection->push(new OpenGraphTag('locale', $seodata->locale));
        }

        if ( $seodata->image ) {
            $collection->push(new OpenGraphTag('image', $seodata->image));

            if ( $seodata->imageMeta ) {
                $collection
                    ->when($seodata->imageMeta->width, fn (self $collection): self => $collection->push(new OpenGraphTag('image:width', $seodata->imageMeta->width)))
                    ->when($seodata->imageMeta->height, fn (self $collection): self => $collection->push(new OpenGraphTag('image:height', $seodata->imageMeta->height)));
            }
        }

        $collection->push(new OpenGraphTag('url', $seodata->url));

        if ( $seodata->site_name ) {
            $collection->push(new OpenGraphTag('site_name', $seodata->site_name));
        }

        if ( $seodata->type ) {
            $collection->push(new OpenGraphTag('type', $seodata->type));
        }

        if ( $seodata->published_time && $seodata->type === 'article' ) {
            $collection->push(new MetaContentTag('article:published_time', $seodata->published_time->toIso8601String()));
        }

        if ( $seodata->modified_time && $seodata->type === 'article' ) {
            $collection->push(new MetaContentTag('article:modified_time', $seodata->modified_time->toIso8601String()));
        }

        if ( $seodata->section && $seodata->type === 'article' ) {
            $collection->push(new MetaContentTag('article:section', $seodata->section));
        }

        if ( $seodata->tags && $seodata->type === 'article' ) {
            foreach ($seodata->tags as $tag) {
                $collection->push(new MetaContentTag('article:tag', $tag));
            }
        }

        return $collection;
    }
}