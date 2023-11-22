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
    public static function initialize(SEOData $SEOData = null): static
    {
        $collection = new static();

        $tags = collect([
            RobotsTag::initialize($SEOData),
            CanonicalTag::initialize($SEOData),
            SitemapTag::initialize($SEOData),
            DescriptionTag::initialize($SEOData),
            AuthorTag::initialize($SEOData),
            TitleTag::initialize($SEOData),
            ImageTag::initialize($SEOData),
            FaviconTag::initialize($SEOData),
            OpenGraphTags::initialize($SEOData),
            TwitterCardTags::initialize($SEOData),
            SchemaTagCollection::initialize($SEOData, $SEOData->schema),
        ])->reject(fn (?Renderable $item): bool => $item === null);

        foreach ($tags as $tag) {
            $collection->push($tag);
        }

        return $collection;
    }
}
