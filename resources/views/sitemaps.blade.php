<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($__sitemaps as $__sitemap)
        <sitemap>
            <loc>{!! htmlspecialchars($__sitemap->getLocation(), ENT_XML1) !!}</loc>
            @if ($__sitemap->getLastModified())
                <lastmod>{!! $__sitemap->getLastModified()->format('Y-m-d\TH:i:sP') !!}</lastmod>
            @endif
        </sitemap>
    @endforeach
</sitemapindex>
