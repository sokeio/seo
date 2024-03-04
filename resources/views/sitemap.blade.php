<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    @if (isset($__hasImages) && $__hasImages) xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" @endif
    @if (isset($__hasVideos) && $__hasVideos) xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" @endif
    @if (isset($__isMultilingual) && $__isMultilingual) xmlns:xhtml="http://www.w3.org/1999/xhtml" @endif>
    @isset($__tags)
        @foreach ($__tags as $__tag)
            <url>
                <loc>{!! htmlspecialchars($__tag->getLocation(), ENT_XML1) !!}</loc>
                @if ($__tag->getLastModified())
                    <lastmod>{{ $__tag->getLastModified()->format('Y-m-d\TH:i:sP') }}</lastmod>
                @endif
                @if ($__tag instanceof \Sokeio\Seo\Sitemap\Tag)
                    @if ($__tag->getChangeFrequency())
                        <changefreq>{{ $__tag->getChangeFrequency() }}</changefreq>
                    @endif
                    @if ($__tag->getPriority())
                        <priority>{{ $__tag->getPriority() }}</priority>
                    @endif
                @endif
                @if ($__tag instanceof \Sokeio\Seo\Sitemap\MultilingualTag)
                    @foreach ($__tag->getMultilingual() as $lang => $href)
                        <xhtml:link rel="alternate" hreflang="{{ $lang }}" href="{{ $href }}" />
                    @endforeach
                @endif
                @if ($__tag instanceof \Sokeio\Seo\Sitemap\ExpiredTag)
                    <expires>
                        <{{ $__tag->getExpired()->format('Y-m-d\TH:i:sP') }}< /expires>
                @endif
                @foreach ($__tag->getImages() as $__image)
                    <image:image>
                        <image:loc>{{ $__image->getLocation() }}</image:loc>
                        @if ($__image->getCaption())
                            <image:caption>{!! htmlspecialchars($__image->getCaption()) !!} </image:caption>
                        @endif
                        @if ($__image->getGeoLocation())
                            <image:geo_location>{!! htmlspecialchars($__image->getGeoLocation()) !!}</image:geo_location>
                        @endif
                        @if ($__image->getTitle())
                            <image:title>{!! htmlspecialchars($__image->getTitle()) !!}</image:title>
                        @endif
                        @if ($__image->getLicense())
                            <image:license>{!! htmlspecialchars($__image->getLicense()) !!}</image:license>
                        @endif
                    </image:image>
                @endforeach
                @foreach ($__tag->getVideos() as $__video)
                    <video:video>
                        @if ($__video->getThumbnailLocation())
                            <video:thumbnail_loc>{!! htmlspecialchars($__video->getThumbnailLocation()) !!}</video:thumbnail_loc>
                        @endif
                        @if ($__video->getTitle())
                            <video:title>{!! htmlspecialchars($__video->getTitle()) !!}</video:title>
                        @endif
                        @if ($__video->getDescription())
                            <video:description>{!! htmlspecialchars($__video->getDescription()) !!}</video:description>
                        @endif
                        @if ($__video->getContentLocation() && !$__video->getPlayerLocation())
                            <video:content_loc>{!! htmlspecialchars($__video->getContentLocation()) !!}</video:content_loc>
                        @endif
                        @if ($__video->getPlayerLocation() && !$__video->getContentLocation())
                            <video:player_loc>{!! htmlspecialchars($__video->getPlayerLocation()) !!}</video:player_loc>
                        @endif
                        @if ($__video->getDuration())
                            <video:duration>{!! $__video->getDuration() !!}</video:duration>
                        @endif
                        @if ($__video->getExpirationDate())
                            <video:expiration_date>{!! $__video->getExpirationDate()->format('Y-m-d\TH:i:sP') !!}</video:expiration_date>
                        @endif
                        @if ($__video->getRating())
                            <video:rating>{!! $__video->getRating() !!}</video:rating>
                        @endif
                        @if ($__video->getViewCount())
                            <video:view_count>{!! $__video->getViewCount() !!}</video:view_count>
                        @endif
                        @if ($__video->getPublicationDate())
                            <video:publication_date>{!! $__video->getPublicationDate()->format('Y-m-d\TH:i:sP') !!}</video:publication_date>
                        @endif
                        <video:family_friendly>{!! $__video->getFamilyFriendly() ? 'yes' : 'no' !!}</video:family_friendly>
                        @if ($__video->getRestriction())
                            <video:restriction relationship="{!! $__video->getRestriction()->getRelationship() !!}">{!! $__video->getRestriction()->getCountries() !!}
                            </video:restriction>
                        @endif
                        @if ($__video->getGalleryLocation())
                            <video:gallery_loc>{!! htmlspecialchars($__video->getGalleryLocation()) !!}</video:gallery_loc>
                        @endif
                        @foreach ($__video->getPrices() as $__price)
                            <video:price currency="{!! $__price->getCurrency() !!}"
                                @if ($__price->getResolution()) resolution="{!! $__price->getResolution() !!}" @endif
                                @if ($__price->getType()) type="{!! $__price->getType() !!}" @endif>
                                {!! $__price->getPrice() !!}
                            </video:price>
                        @endforeach
                        <video:requires_subscription>{!! $__video->getRequiresSubscription() ? 'yes' : 'no' !!}</video:requires_subscription>
                        @if ($__video->getUploader())
                            <video:uploader>{!! htmlspecialchars($__video->getUploader()) !!}</video:uploader>
                        @endif
                        <video:live>{!! $__video->getLive() ? 'yes' : 'no' !!}</video:live>
                    </video:video>
                @endforeach
            </url>
        @endforeach
    @endisset
</urlset>
