<?php

namespace BytePlatform\Seo;

use Illuminate\Support\Collection;

class SchemaCollection extends Collection
{
    public static function initialize(): static
    {
        return new static();
    }
}
