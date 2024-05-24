<?php

namespace Sokeio\Seo\Schema;

use Sokeio\Seo\SEOData;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ArticleSchema extends Schema
{
    public array $authors = [];

    public ?CarbonInterface $datePublished = null;

    public ?CarbonInterface $dateModified = null;

    public ?string $description = null;

    public ?string $headline = null;

    public ?string $image = null;

    public string $type = 'Article';

    public ?string $url = null;

    public ?string $articleBody = null;

    public function addAuthor(string $authorName): static
    {
        if (empty($this->authors)) {
            $this->authors = [
                '@type' => 'Person',
                'name' => $authorName,
            ];

            return $this;
        }

        $this->authors = [
            $this->authors,
            [
                '@type' => 'Person',
                'name' => $authorName,
            ],
        ];

        return $this;
    }

    public function initializeMarkup(SEOData $seodata, array $markupBuilders): void
    {
        $this->url = $seodata->url;

        $properties = [
            'headline' => 'title',
            'description' => 'description',
            'image' => 'image',
            'datePublished' => 'datePublished',
            'dateModified' => 'dateModified',
            'articleBody' => 'articleBody',
        ];

        foreach ($properties as $markupProperty => $seodataProperty) {
            if ($seodata->{$seodataProperty}) {
                $this->{$markupProperty} = $seodata->{$seodataProperty};
            }
        }

        if ($seodata->author) {
            $this->authors = [
                '@type' => 'Person',
                'name' => $seodata->author,
            ];
        }
    }

    public function generateInner(): string
    {
        return collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->url,
            ],
            'datePublished' => ($this->datePublished ?? Carbon::now())->toIso8601String(),
            'dateModified' => ($this->dateModified ?? Carbon::now())->toIso8601String(),
            'headline' => $this->headline,
        ])
            ->when(
                $this->authors,
                fn (Collection $collection): Collection =>
                $collection->put('author', $this->authors)
            )
            ->when(
                $this->description,
                fn (Collection $collection): Collection =>
                $collection->put('description', $this->description)
            )
            ->when($this->image, fn (Collection $collection): Collection =>
            $collection->put(
                'image',
                $this->image
            ))
            ->when(
                $this->articleBody,
                fn (Collection $collection): Collection =>
                $collection->put('articleBody', $this->articleBody)
            )
            ->pipeThrough($this->markupTransformers)
            ->toJson();
    }
}
