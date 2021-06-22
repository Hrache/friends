<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\FriendsController;
use App\Http\Controllers\SearchFindController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

# Friends
Route::prefix('/friends')->name('friends.')->middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/', [FriendsController::class, 'index'])->name('index');
    Route::post('/store', [FriendsController::class, 'friendRequest'])->name('store');
    Route::post('/check-friendship', [FriendsController::class, 'checkFriendship'])->name('check-friendship');
    Route::delete('/{friend}/delete', [FriendsController::class, 'delete'])->name('delete');
    Route::put('/reject', [FriendsController::class, 'reject'])->name('reject');
    Route::put('/confirm', [FriendsController::class, 'confirm'])->name('confirm');
    Route::delete('/{cancel}/delete', [FriendsController::class, 'cancel'])->name('cancel');
});

# Search
Route::prefix('/search')->name('search.')->group(function() {
    Route::post('/people', [SearchFindController::class, 'searchPeople'])->name('people');
    Route::get('/friend', [SearchFindController::class, 'searchFriend'])->name('friend');
});
