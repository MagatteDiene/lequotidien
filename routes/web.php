<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ApiTokenController;
use Illuminate\Support\Facades\Route;

// SOAP Service (CSRF exclu dans bootstrap/app.php)
Route::match(['get', 'post'], '/soap', [SoapController::class, 'handle'])->name('soap');

// Publiques
Route::get('/', [AccueilController::class, 'index'])->name('accueil');
Route::get('/article/{slug}', [AccueilController::class, 'show'])->name('article.show');
Route::get('/categorie/{slug}', [AccueilController::class, 'parCategorie'])->name('categorie.articles');

// Auth (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Auth (auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profil (tous les utilisateurs connectés)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin (editeur + administrateur)
    Route::middleware('role:editeur,administrateur')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            $stats = [
                'articles' => \App\Models\Article::count(),
                'publies' => \App\Models\Article::publie()->count(),
                'categories' => \App\Models\Categorie::count(),
                'users' => \App\Models\User::count(),
            ];
            $derniersArticles = \App\Models\Article::with(['categorie', 'auteur'])->latest()->take(5)->get();
            return view('admin.dashboard', compact('stats', 'derniersArticles'));
        })->name('dashboard');

        Route::resource('articles', AdminArticleController::class);
        Route::resource('categories', AdminCategorieController::class);
    });

    // Admin (administrateur seulement)
    Route::middleware('role:administrateur')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
        
        Route::get('/tokens', [ApiTokenController::class, 'index'])->name('tokens.index');
        Route::post('/tokens', [ApiTokenController::class, 'store'])->name('tokens.store');
        Route::patch('/tokens/{apiToken}/toggle', [ApiTokenController::class, 'toggle'])->name('tokens.toggle');
        Route::delete('/tokens/{apiToken}', [ApiTokenController::class, 'destroy'])->name('tokens.destroy');
    });
});
