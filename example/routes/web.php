<?php

use BytePlatform\Seo\Facades\SEO;
use BytePlatform\Seo\Facades\Sitemap;
use BytePlatform\Seo\SchemaCollection;
use BytePlatform\Seo\Schemas\ArticleSchema;
use BytePlatform\Seo\SEOData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    SEO::SEODataTransformer(function (SEOData $data) {
        $data->schema = SchemaCollection::initialize()->addArticle();
        $data->published_time = Carbon::now();
        $data->modified_time= Carbon::now();
        return $data;
    });
    return view('welcome');
});
