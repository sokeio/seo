<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\MetaTag;

class ImageTag extends MetaTag
{
    public static function initialize(?SEOData $seodata): MetaTag|null
    {
        $image = $seodata?->image;

        if (!$image) {
            return null;
        }

        return new MetaTag(
            name: 'image',
            content: trim($image)
        );
    }
}
