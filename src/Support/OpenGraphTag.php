<?php

namespace BytePlatform\Seo\Support;

use Illuminate\Support\Collection;

class OpenGraphTag extends Tag
{
    public string $tag = 'meta';

    public function __construct(
        public string $name,
        public string $content,
    ) {
        $this->attributesPipeline[] = function (Collection $collection) {
            return $collection->mapWithKeys(function ($value, $key) {
                if ($key === 'property') {
                    $value = 'og:' . $value;
                }

                return [$key => $value];
            });
        };
    }
}
