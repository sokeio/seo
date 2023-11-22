<?php

use Sokeio\Seo\Facades\SEO;
use Sokeio\Seo\SEOData;
use Sokeio\Seo\TagManager;
use Illuminate\Database\Eloquent\Model;

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
