<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


Route::get('/games', [GameController::class, 'index'])
    ->name('games.index');


Route::post('/games/store', [GameController::class, 'store'])
    ->name('games.store');


Route::get('/games/leaderboard', [GameController::class, 'leaderboard'])
    ->name('games.leaderboard');


Route::get('/games/all', [GameController::class, 'all'])
    ->name('games.all');


Route::get('/games/partials/{type}', function (string $type) {
    $allowed = ['speed', 'race', 'letters', 'random-words', 'arabic-typing'];
    abort_unless(in_array($type, $allowed, true), 404);
    return view("games.partials.$type");
})->name('games.partials');


Route::get('/games/{type}', [GameController::class, 'show'])
    ->whereIn('type', ['speed', 'race', 'letters', 'random-words', 'arabic-typing'])
    ->name('games.show');
