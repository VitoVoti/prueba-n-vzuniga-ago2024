<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleTagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepoController;
use App\Http\Controllers\RepoTagController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('articles', ArticleController::class);
    Route::resource('tags', TagController::class, ['only' => ['index', 'show']]);

    //Route::get('repos', [RepoController::class, 'index'])->name('repos.index');
    Route::resource('repos', RepoController::class, ['only' => ['index', 'update']]);
    
    Route::post('article_tags', [ArticleTagController::class,'store'])->name('article_tags.store');
    Route::delete('article_tags', [ArticleTagController::class,'destroy'])->name('article_tags.destroy');
    Route::post('repo_tags', [RepoTagController::class, 'store'])->name('repo_tags.store');
    Route::delete('repo_tags', [RepoTagController::class, 'destroy'])->name('repo_tags.destroy');
    
});

Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::resource('tags', TagController::class, ['except' => ['index', 'show']]);
});

require __DIR__.'/auth.php';
