<?php

use Illuminate\Support\Facades\Route;
use HolyMotors\InlineRelationships\Http\Controllers\NewResourceController;

/**
 * 
 * Custom Nova routes
 * 
 */

Route::prefix('holy-motors')->group(function () {
    Route::prefix('inline-relationships')->group(function () {
        Route::get('/{resourceName}/{resourceId}', NewResourceController::class);
    });
});
