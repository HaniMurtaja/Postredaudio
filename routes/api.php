<?php

use App\Http\Controllers\ResourceController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SitemapResources;
use Illuminate\Support\Facades\Route;

Route::prefix('data')->controller(ResourceController::class)->group(function () {
    Route::get('/gallery', 'gallery');
    Route::prefix('projects')->group(function () {
        Route::get('/', 'projects');
        Route::get('/{slug}', 'project');
    });
    Route::prefix('stories')->group(function () {
        Route::get('/', 'stories');
        Route::get('/{slug}', 'story');
    });
    Route::prefix('services')->group(function () {
        Route::get('/', 'services');
        Route::get('/{slug}', 'service');
    });
    Route::prefix('industries')->group(function () {
        Route::get('/', 'industries');
        Route::get('/{slug}', 'industry');
    });
    Route::get('/vacancies', 'vacancies');
    Route::prefix('pages')->group(function () {
        Route::get('/', 'pages');
        Route::get('/{slug}', 'page');
    });
    Route::get('/contact', 'contact');
    Route::get('/resources', 'count');
    Route::get('/base-64', [ImageController::class, 'images']);
    Route::get('/sitemap-content', [SitemapResources::class, 'getDynamicUrls']);
});
Route::prefix('form')->controller(FormController::class)->group(function () {
    Route::post('/contact', 'contactForm');
    Route::post('/vacancy', 'vacancyForm');
});

Route::get('setup', function () {
    if (!\App\Models\Page::where('name', 'services')->exists()) {
        \App\Models\Page::create([
            'name' => 'services',
            'page_type' => '1'
        ]);
        echo 'Services page created';
        echo '<br>';
    }
    if (!\App\Models\Page::where('name', 'industries')->exists()) {
        \App\Models\Page::create([
            'name' => 'industries',
            'page_type' => '1'
        ]);
        echo 'Industries page created';
        echo '<br>';
    }
    if (!\App\Models\Page::where('name', 'projects')->exists()) {
        \App\Models\Page::create([
            'name' => 'projects',
            'page_type' => '1'
        ]);
        echo 'Projects page created';
        echo '<br>';
    }
    if (!\App\Models\Page::where('name', 'stories')->exists()) {
        \App\Models\Page::create([
            'name' => 'stories',
            'page_type' => '1'
        ]);
        echo 'Stories page created';
        echo '<br>';
    }
    if (!\App\Models\Page::where('name', 'contact')->exists()) {
        \App\Models\Page::create([
            'name' => 'contact',
            'page_type' => '1'
        ]);
        echo 'Contact page created';
        echo '<br>';
    }
});
