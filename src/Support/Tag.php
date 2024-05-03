<?php

namespace Sokeio\Seo\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

abstract class Tag implements Renderable
{
    protected static array $reservedAttributes = [
        'tag',
        'inner',
        'attributesPipeline',
        'attributes',
    ];

    public string $tag;

    public array $attributesPipeline = [];
    protected $inner;
    protected $attributes;

    public function render(): View
    {
        return view("seo::tag", [
            'tag' => $this->tag,
            'attributes' => $this->collectAttributes(),
            'inner' => $this->inner ?? null,
        ]);
    }

    public function collectAttributes(): Collection
    {
        return collect($this->attributes ?? get_object_vars($this))
            ->except(static::$reservedAttributes)
            ->pipe(function (Collection $attributes) {
                $reservedAttributes = $attributes->only('property', 'name', 'rel');

                return $reservedAttributes->merge($attributes->except('property', 'name', 'rel')->sortKeys());
            })
            ->pipeThrough($this->attributesPipeline);
    }
}
