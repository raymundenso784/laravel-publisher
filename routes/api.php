<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
    });
});
