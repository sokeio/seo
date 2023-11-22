<?php

namespace Sokeio\Seo\Schemas;

use Sokeio\Laravel\Pipe\Pipeable;
use Sokeio\Seo\SEOData;
use Sokeio\Seo\Support\Tag;
use Closure;
use Illuminate\Support\Collection;

abstract class Schema extends Tag
{
    use Pipeable;

    public array $attributes = [
        'type' => 'application/ld+json',
    ];

    public string $context = 'https://schema.org/';

    public Collection $markup;

    public array $markupTransformers = [];

    public string $tag = 'script';

    public function __construct(SEOData $SEOData, array $markupBuilders = [])
    {
        $this->initializeMarkup($SEOData, $markupBuilders);

        $this->pipeThrough($markupBuilders);

        $this->inner = $this->generateInner();
    }

    abstract public function generateInner(): string;

    abstract public function initializeMarkup(SEOData $SEOData, array $markupBuilders): void;

    public function markup(Closure $transformer): static
    {
        $this->markupTransformers[] = $transformer;

        return $this;
    }
}