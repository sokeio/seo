<?php

namespace BytePlatform\Seo;

use BytePlatform\Seo\Submits\SubmitManager;
use Closure;

class SEOManager
{
    /**
     * Changes every time it is accessed.
     *
     * @var string
     */
    public const SITEMAP_ALWAYS = 'always';

    /**
     * Changes hourly.
     *
     * @var string
     */
    public const SITEMAP_HOURLY = 'hourly';

    /**
     * Changes daily.
     *
     * @var string
     */
    public const SITEMAP_DAILY = 'daily';

    /**
     * Changes weekly.
     *
     * @var string
     */
    public const SITEMAP_WEEKLY = 'weekly';

    /**
     * Changes monthly.
     *
     * @var string
     */
    public const SITEMAP_MONTHLY = 'monthly';

    /**
     * Changes yearly.
     *
     * @var string
     */
    public const SITEMAP_YEARLY = 'yearly';

    /**
     * Never changes, archived content.
     *
     * @var string
     */
    public const SITEMAP_NEVER = 'never';

    protected array $tagTransformers = [];

    protected array $SEODataTransformers = [];

    public function SEODataTransformer(Closure $transformer): static
    {
        $this->SEODataTransformers[] = $transformer;

        return $this;
    }

    public function tagTransformer(Closure $transformer): static
    {
        $this->tagTransformers[] = $transformer;

        return $this;
    }

    public function getTagTransformers(): array
    {
        return $this->tagTransformers;
    }

    public function getSEODataTransformers(): array
    {
        return $this->SEODataTransformers;
    }
    public function SendSitemap($sitemap, $append = [])
    {
        return (new SubmitManager($append))->sendSitemap($sitemap);
    }
    public function IndexNow($url)
    {
        return (new SubmitManager([]))->index($url);
    }
}
