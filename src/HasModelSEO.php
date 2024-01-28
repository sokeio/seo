<?php

namespace Sokeio\Seo;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasModelSEO
{
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
