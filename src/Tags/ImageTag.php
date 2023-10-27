<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\Support\MetaTag;

class ImageTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $image = $SEOData?->image;

        if (!$image) {
            return null;
        }

        return new MetaTag(
            name: 'image',
            content: trim($image)
        );
    }
}
