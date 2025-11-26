<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    LessonController,
    TypingController,
    ProfileController,
    ArticleController
};

// Public pages
Route::get('/', [ArticleController::class, 'welcome'])->name('home');
Route::get('/article/{id}', [ArticleController::class, 'show'])->whereNumber('id')->name('article.show');

// Redirect after login
Route::get('/redirect-after-login', function () {
    $user = auth()->user();
    if (!$user) return redirect()->route('login');
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('dashboard'),
        default => redirect()->route('home'),
    };
})->name('redirect.after.login');

// User panel (protected)
Route::middleware(['auth', 'role:user'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lessons
    Route::prefix('lessons')->name('lessons.')->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('index');
        Route::get('/{lesson}', [LessonController::class, 'show'])->whereNumber('lesson')->name('show');
    });

    // Typing practice
    Route::prefix('typing')->name('typing.')->group(function () {
        Route::get('/{lesson}', [TypingController::class, 'show'])->whereNumber('lesson')->name('show');
        Route::post('/attempts', [TypingController::class, 'storeAttempt'])->name('attempts.store');
        Route::get('/stats', [TypingController::class, 'stats'])->name('stats');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Include auth and games routes
require __DIR__ . '/games.php';
require __DIR__ . '/auth.php';

// Fallback 404 page
Route::fallback(fn() => response()->view('errors.404', [], 404));
