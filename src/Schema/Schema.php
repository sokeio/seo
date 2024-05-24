<?php

namespace Sokeio\Seo\Schema;

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
    public $inner;

    public function __construct(SEOData $seodata, array $markupBuilders = [])
    {
        $this->initializeMarkup($seodata, $markupBuilders);

        $this->pipeThrough($markupBuilders);

        $this->inner = $this->generateInner();
    }

    abstract public function generateInner(): string;

    abstract public function initializeMarkup(SEOData $seodata, array $markupBuilders): void;

    public function markup(Closure $transformer): static
    {
        $this->markupTransformers[] = $transformer;

        return $this;
    }
}
