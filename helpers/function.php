<?php

use BytePlatform\Seo\SEOData;
use BytePlatform\Seo\TagManager;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('seo_header_render')) {
    function seo_header_render(Model|SEOData $source = null)
    {
        $tagManager = app(TagManager::class);

        if ($source) {
            $tagManager->for($source);
        }

        return $tagManager;
    }
}
