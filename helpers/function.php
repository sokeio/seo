<?php

use Sokeio\Seo\Facades\SEO;
use Sokeio\Seo\SEOData;
use Sokeio\Seo\TagManager;
use Illuminate\Database\Eloquent\Model;
use Sokeio\Seo\SEOManager;

if (!function_exists('seo_header_render')) {
    function seo_header_render(Model|SEOData|null $source = null)
    {
        $tagManager = app(TagManager::class);
        if (!$source) $source = SEO::getSource();
        if ($source) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}
if (!function_exists('SeoHelper')) {
    function SeoHelper(): SEOManager
    {
        return SEO::getFacadeRoot();
    }
}
