<?php

namespace Sokeio\Seo;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSEO
{
    public function getSeoTitle()
    {
        return $this->name;
    }
    public function getSeoDescription()
    {
        return $this->description;
    }
    public function getSeoImage()
    {
        return $this->image;
    }
    public function getSeoAuthor()
    {
        return $this->author ?? '';
    }
    public function getSeoRobots()
    {
        return $this->robots ?? '';
    }

    public function getSeoCanonicalUrl()
    {
        return $this->canonical_url ?? '';
    }
    public function prepareForUsage($overrides = null): SEOData
    {
        if (method_exists($this, 'getDynamicSEOData')) {
            /** @var SEOData $overrides */
            $overrides = $this->getDynamicSEOData();
        }

        if (method_exists($this, 'enableTitleSuffix')) {
            $enableTitleSuffix = $this->enableTitleSuffix();
        } elseif (property_exists($this, 'enableTitleSuffix')) {
            $enableTitleSuffix = $this->enableTitleSuffix;
        }

        return new SEOData(
            title: $overrides?->title ?? $this->getSeoTitle(),
            description: $overrides?->description ?? $this->getSeoDescription(),
            author: $overrides?->author ?? $this->getSeoAuthor(),
            image: $overrides?->image ?? $this->getSeoImage(),
            url: $overrides?->url ?? $this->getSeoCanonicalUrl(),
            enableTitleSuffix: $enableTitleSuffix ?? true,
            datePublished: $overrides?->datePublished ?? ($this?->created_at ?? null),
            dateModified: $overrides?->dateModified ?? ($this?->updated_at ?? null),
            articleBody: $overrides?->articleBody ?? null,
            section: $overrides?->section ?? null,
            tags: $overrides?->tags ?? null,
            schema: $overrides?->schema ?? null,
            type: $overrides?->type ?? null,
            locale: $overrides?->locale ?? null,
            robots: $overrides?->robots ?? $this->robots,
            canonical_url: $overrides?->canonical_url ?? $this->canonical_url,
        );
    }
}
