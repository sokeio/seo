<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\Tag;

class TitleTag extends Tag
{
    public string $tag = 'title';

    public function __construct(
        public string $inner,
    ) {
    }

    public static function initialize(?SEOData $seodata): Tag|null
    {
        $title = $seodata?->title;

        if (!$title) {
            return null;
        }

        return new static(
            inner: trim($title),
        );
    }
}
