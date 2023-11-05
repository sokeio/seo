<?php

namespace BytePlatform\Seo\Models;

use BytePlatform\Seo\SEOData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SEO extends Model
{
    protected $guarded = [];

    public $table = 'seo';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function prepareForUsage($overrides = null): SEOData
    {
        if (method_exists($this->model, 'getDynamicSEOData')) {
            /** @var SEOData $overrides */
            $overrides = $this->model->getDynamicSEOData();
        }

        if (method_exists($this->model, 'enableTitleSuffix')) {
            $enableTitleSuffix = $this->model->enableTitleSuffix();
        } elseif (property_exists($this->model, 'enableTitleSuffix')) {
            $enableTitleSuffix = $this->model->enableTitleSuffix;
        }

        return new SEOData(
            title: $overrides?->title ?? $this->title,
            description: $overrides?->description ?? $this->description,
            author: $overrides?->author ?? $this->author,
            image: $overrides?->image ?? $this->image,
            url: $overrides?->url ?? null,
            enableTitleSuffix: $enableTitleSuffix ?? true,
            datePublished: $overrides?->datePublished ?? ($this->model?->created_at ?? null),
            dateModified: $overrides?->dateModified ?? ($this->model?->updated_at ?? null),
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
    public function fillForSeo(SEOData $dataSEO = null): SEOData
    {
        $dataSEO->title = $this->title ?? $dataSEO->title;
        $dataSEO->description = $this->description ?? $dataSEO->description;
        $dataSEO->author = $this->author ?? $dataSEO->author;
        $dataSEO->image = $this->image ?? $dataSEO->image;
        $dataSEO->datePublished = $this->model?->created_at  ?? $dataSEO->datePublished;
        $dataSEO->dateModified = $this->model?->updated_at  ?? $dataSEO->dateModified;
        $dataSEO->robots = $this->robots ?? $dataSEO->robots;
        $dataSEO->canonical_url = $this->canonical_url ?? $dataSEO->canonical_url;
        return $dataSEO;
    }
}
