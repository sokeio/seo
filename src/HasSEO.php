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
    public function addSEO(): static
    {
        $this->seo()->create([
            'title' => $this->getSeoTitle(),
            'description' => $this->getSeoDescription(),
            'image' => $this->getSeoImage(),
            'author' => $this->getSeoAuthor(),
            'robots' => $this->getSeoRobots(),
            'canonical_url' => $this->getSeoCanonicalUrl()
        ]);

        return $this;
    }

    protected static function bootHasSEO(): void
    {
        static::created(fn (self $model): self => $model->addSEO());
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(config('seo.model'), 'model')->withDefault();
    }
}
