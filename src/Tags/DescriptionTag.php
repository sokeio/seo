<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\MetaTag;


class DescriptionTag extends MetaTag
{
    public static function initialize(?SEOData $seodata): MetaTag|null
    {
        $description = $seodata?->description;

        if (!$description) {
            return null;
        }

        return new MetaTag(
            name: 'description',
            content: trim($description)
        );
    }
}
