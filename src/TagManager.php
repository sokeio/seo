<?php

namespace Sokeio\Seo;

use Sokeio\Seo\Facades\SEO;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

class TagManager implements Renderable
{
    public Model $model;

    public SEOData $seodata;

    public TagCollection $tags;

    public function __construct()
    {
        $this->tags = TagCollection::initialize(
            $this->fillSEOData()
        );
    }

    public function fillSEOData(SEOData $seodata = null): SEOData
    {
        $seodata ??= new SEOData();
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
            if ($seodata->{$property} === null) {
                $seodata->{$property} = $defaultValue;
            }
        }

        if ($seodata->enableTitleSuffix) {
            $seodata->title .= config('seo.title.suffix');
        }

        if ($seodata->image && !filter_var($seodata->image, FILTER_VALIDATE_URL)) {
            $seodata->imageMeta();

            $seodata->image = secure_url($seodata->image);
        }

        if ($seodata->favicon && !filter_var($seodata->favicon, FILTER_VALIDATE_URL)) {
            $seodata->favicon = secure_url($seodata->favicon);
        }

        if (!$seodata->url) {
            $seodata->url = url()->current();
        }

        if ($seodata->url === url('/') && ($homepageTitle = config('seo.title.homepage_title'))) {
            $seodata->title = $homepageTitle;
        }

        return $seodata->pipethrough(
            SEO::getDataTransformers()
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
        $seodata = isset($this->model)
            ? (isset($this->model->seo) ? $this->model->seo?->prepareForUsage() : $this->model->prepareForUsage())
            : $this->SEOData;

        $this->tags = TagCollection::initialize(
            $this->fillSEOData($seodata ?? new SEOData())
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
