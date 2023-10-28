<?php

namespace BytePlatform\Seo;

use BytePlatform\Seo\Tags\AuthorTag;
use BytePlatform\Seo\Tags\CanonicalTag;
use BytePlatform\Seo\Tags\DescriptionTag;
use BytePlatform\Seo\Tags\FaviconTag;
use BytePlatform\Seo\Tags\ImageTag;
use BytePlatform\Seo\Tags\OpenGraphTags;
use BytePlatform\Seo\Tags\RobotsTag;
use BytePlatform\Seo\Tags\SchemaTagCollection;
use BytePlatform\Seo\Tags\SitemapTag;
use BytePlatform\Seo\Tags\TitleTag;
use BytePlatform\Seo\Tags\TwitterCardTags;
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
