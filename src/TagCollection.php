<?php

namespace Sokeio\Seo;

use Sokeio\Seo\Tags\AuthorTag;
use Sokeio\Seo\Tags\CanonicalTag;
use Sokeio\Seo\Tags\DescriptionTag;
use Sokeio\Seo\Tags\FaviconTag;
use Sokeio\Seo\Tags\ImageTag;
use Sokeio\Seo\Tags\OpenGraphTags;
use Sokeio\Seo\Tags\RobotsTag;
use Sokeio\Seo\Tags\SchemaTagCollection;
use Sokeio\Seo\Tags\SitemapTag;
use Sokeio\Seo\Tags\TitleTag;
use Sokeio\Seo\Tags\TwitterCardTags;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class TagCollection extends Collection
{
    public static function initialize(SEOData $seodata = null): static
    {
        $collection = new static();

        $tags = collect([
            RobotsTag::initialize($seodata),
            CanonicalTag::initialize($seodata),
            SitemapTag::initialize($seodata),
            TitleTag::initialize($seodata),
            DescriptionTag::initialize($seodata),
            AuthorTag::initialize($seodata),
            ImageTag::initialize($seodata),
            FaviconTag::initialize($seodata),
            OpenGraphTags::initialize($seodata),
            TwitterCardTags::initialize($seodata),
            SchemaTagCollection::initialize($seodata, $seodata->schema),
        ])->reject(fn (?Renderable $item): bool => $item === null);

        foreach ($tags as $tag) {
            $collection->push($tag);
        }

        return $collection;
    }
}
