<?php

namespace BytePlatform\Seo\Tags;

use BytePlatform\Seo\SEOData;

class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        public string $inner,
    ) {
    }

    public static function initialize(?SEOData $SEOData): Tag|null
    {
        $title = $SEOData?->title;

        if (!$title) {
            return null;
        }

        return new static(
            inner: trim($title),
        );
    }
}
