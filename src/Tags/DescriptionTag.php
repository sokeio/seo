<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\Support\MetaTag;


class DescriptionTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $description = $SEOData?->description;

        if (!$description) {
            return null;
        }

        return new MetaTag(
            name: 'description',
            content: trim($description)
        );
    }
}
