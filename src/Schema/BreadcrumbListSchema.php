<?php

namespace Sokeio\Seo\Schema;

use Sokeio\Seo\SEOData;
use Illuminate\Support\Collection;


class BreadcrumbListSchema extends Schema
{
    public Collection $breadcrumbs;

    public string $type = 'BreadcrumbList';

    public function appendBreadcrumbs(array $breadcrumbs): static
    {
        foreach ($breadcrumbs as $page => $url) {
            $this->breadcrumbs->put($page, $url);
        }

        return $this;
    }

    public function initializeMarkup(SEOData $seodata, array $markupBuilders): void
    {
        $this->breadcrumbs = collect([
            $seodata->title => $seodata->url,
        ]);
    }

    public function generateInner(): string
    {
        return collect([
            '@context' => 'https://schema.org',
            '@type' => $this->type,
            'itemListElement' => $this->breadcrumbs
                ->reduce(function (Collection $carry, string $url, string $pagename): Collection {
                    return $carry->push([
                        '@type' => 'ListItem',
                        'position' => $carry->count() + 1,
                        'name' => $pagename,
                        'item' => $url,
                    ]);
                }, new Collection()),
        ])
            ->pipeThrough($this->markupTransformers)
            ->toJson();
    }

    public function prependBreadcrumbs(array $breadcrumbs): static
    {
        foreach (array_reverse($breadcrumbs) as $pagename => $url) {
            $this->breadcrumbs->prepend($url, $pagename);
        }

        return $this;
    }
}
