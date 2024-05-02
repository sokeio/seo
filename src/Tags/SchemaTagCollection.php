<?php

namespace Sokeio\Seo\Tags;

use Sokeio\Seo\SchemaCollection;
use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\RenderableCollection;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class SchemaTagCollection extends Collection implements Renderable
{
    use RenderableCollection;

    public static function initialize(SEOData $seodata, SchemaCollection $schema = null): ?static
    {
        $collection = new static();

        if (!$schema) {
            return null;
        }

        foreach ($schema->markup as $markupClass => $markupBuilders) {
            $collection = $collection->push(new $markupClass($seodata, $markupBuilders));
        }

        return $collection;
    }
}
