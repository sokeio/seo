<?php

namespace Sokeio\Seo;

use Sokeio\Seo\Facades\SEO;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class TagManager implements Renderable
{
    public Model $model;

    public SEOData $SEOData;

    public TagCollection $tags;

    public function __construct()
    {
        $this->tags = TagCollection::initialize(
            $this->fillSEOData()
        );
    }

    public function fillSEOData(SEOData $SEOData = null): SEOData
    {
        $SEOData ??= new SEOData();
        $defaults = applyFilters("SEO_DATA_DEFAULT", [
            'title' => (config('seo.title.infer_title_from_url') ? $this->inferTitleFromUrl() : null),
            'description' => config('seo.description.fallback'),
            'image' => config('seo.image.fallback'),
            'site_name' => config('seo.site_name'),
            'author' => config('seo.author.fallback'),
            'twitter_username' => str(config('seo.twitter.@username'))->start('@'),
            'favicon' => config('seo.favicon'),
        ]);

        foreach ($defaults as $property => $defaultValue) {
            if ($SEOData->{$property} === null) {
                $SEOData->{$property} = $defaultValue;
            }
        }

        if ($SEOData->enableTitleSuffix) {
            $SEOData->title .= config('seo.title.suffix');
        }

        if ($SEOData->image && !filter_var($SEOData->image, FILTER_VALIDATE_URL)) {
            $SEOData->imageMeta();

            $SEOData->image = secure_url($SEOData->image);
        }

        if ($SEOData->favicon && !filter_var($SEOData->favicon, FILTER_VALIDATE_URL)) {
            $SEOData->favicon = secure_url($SEOData->favicon);
        }

        if (!$SEOData->url) {
            $SEOData->url = url()->current();
        }

        if ($SEOData->url === url('/') && ($homepageTitle = config('seo.title.homepage_title'))) {
            $SEOData->title = $homepageTitle;
        }

        return $SEOData->pipethrough(
            SEO::getSEODataTransformers()
        );
    }

    public function for(Model|SEOData $source): static
    {
        if ($source instanceof Model) {
            $this->model = $source;
            unset($this->SEOData);
        } elseif ($source instanceof SEOData) {
            unset($this->model);
            $this->SEOData = $source;
        }

        // The tags collection is already initialized when constructing the manager. Here, we'll
        // initialize the collection again, but this time we pass the model to the initializer.
        // The initializes will pass the generated SEOData to all underlying initializers, ensuring that
        // the tags are always fully up-to-date and no remnants from previous initializations are present.
        $SEOData = isset($this->model)
            ? (isset($this->model->seo) ? $this->model->seo?->prepareForUsage() : $this->model->prepareForUsage())
            : $this->SEOData;

        $this->tags = TagCollection::initialize(
            $this->fillSEOData($SEOData ?? new SEOData())
        );

        return $this;
    }

    protected function inferTitleFromUrl(): string
    {
        return str(url()->current())
            ->afterLast('/')
            ->headline();
    }

    public function render(): string
    {
        return $this->tags
            ->pipeThrough(SEO::getTagTransformers())
            ->reduce(function (string $carry, Renderable $item) {
                return $carry .= str($item->render())->trim() . PHP_EOL;
            }, '');
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
