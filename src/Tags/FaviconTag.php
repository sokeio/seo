<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\LinkTag;
use Illuminate\Support\Collection;

class FaviconTag extends LinkTag
{
    public static function initialize(?SEOData $seodata): static|null
    {
        $favicon = $seodata?->favicon;

        if (!$favicon) {
            return null;
        }

        return new static(
            rel: 'shortcut icon',
            href: $favicon,
        );
    }

    public function collectAttributes(): Collection
    {
        return parent::collectAttributes()
            ->sortKeys();
    }
}
