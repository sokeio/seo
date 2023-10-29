<?php

namespace BytePlatform\Seo\Submits;

class SubmitManager
{

    public function index(string $engine, string $apiKey, array $urls, $host): bool
    {
        $ch = curl_init("https://{$engine}/indexnow");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['host' => $host, 'key' => $apiKey, 'urlList' => $urls]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['content-type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $code >= 200 && $code < 300;
    }
    /**
     * Inform search engine
     *
     * @param  string $engine
     * @param  string $url
     * @return void
     */
    public function inform(string $engine, string $url): void
    {
        $req = curl_init("https://{$engine}/ping?sitemap={$url}");
        curl_setopt($req, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($req);
        curl_close($req);
    }
    /**
     * Send  url to IndexNow
     *
     * @param  string| array $url
     * @param  array $engines
     * @return void
     */
    public function sendUrl(string| array $url, $host, $engines = []): void
    {
        if (!is_array($url)) $url = [$url];
        foreach (array_merge(config('seo.submit.indexNow',  []), $engines ?? [])  as $engine => $key) {
            $this->index($engine, $key, $url, $host);
        }
    }
    /**
     * Send sitemap url to registred engines
     *
     * @param  string $sitemapUrl
     * @param  array $engines
     * @return void
     */
    public function sendSitemap(string $sitemapUrl, $engines = []): void
    {
        foreach (array_merge(config('seo.submit.index',  []), $engines ?? [])  as $engine) {
            $this->inform($engine, $sitemapUrl);
        }
    }
}
