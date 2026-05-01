<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->group(function () {
    Route::get('/articles',                        [ArticleController::class, 'index']);
    Route::get('/articles/categories',             [ArticleController::class, 'parCategories']);
    Route::get('/articles/categorie/{slug}',       [ArticleController::class, 'parCategorie']);
});
