<?php

use BytePlatform\Seo\Facades\SEO;
use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\TagManager;
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
