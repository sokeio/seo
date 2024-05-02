<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\MetaTag;

class AuthorTag extends MetaTag
{
    public static function initialize(?SEOData $seodata): MetaTag|null
    {
        $author = $seodata?->author;

        if (!$author) {
            return null;
        }

        return new MetaTag(
            name: 'author',
            content: trim($author)
        );
    }
}
