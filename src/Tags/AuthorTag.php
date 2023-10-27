<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;

class AuthorTag extends MetaTag
{
    public static function initialize(?SEOData $SEOData): MetaTag|null
    {
        $author = $SEOData?->author;

        if ( ! $author ) {
            return null;
        }

        return new MetaTag(
            name: 'author',
            content: trim($author)
        );
    }
}