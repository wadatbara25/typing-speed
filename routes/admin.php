<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminLessonController,
    AdminUserController,
    AdminArticleController,
    AdminStatisticsController
};


// Dashboard
Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

// Lessons management
Route::resource('lessons', AdminLessonController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names([
        'index'   => 'lessons.index',
        'create'  => 'lessons.create',
        'store'   => 'lessons.store',
        'edit'    => 'lessons.edit',
        'update'  => 'lessons.update',
        'destroy' => 'lessons.destroy',
    ]);

// Users management
Route::resource('users', AdminUserController::class)
    ->only(['index', 'show', 'destroy'])
    ->names([
        'index'   => 'users.index',
        'show'    => 'users.show',
        'destroy' => 'users.destroy',
    ]);

// Articles management
Route::resource('articles', AdminArticleController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
    ->names([
        'index'   => 'articles.index',
        'create'  => 'articles.create',
        'store'   => 'articles.store',
        'edit'    => 'articles.edit',
        'update'  => 'articles.update',
        'destroy' => 'articles.destroy',
    ]);

// Statistics
Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('statistics');
