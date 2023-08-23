<?php

use App\Http\Controllers\Category\CreateController;
use App\Http\Controllers\Category\DeleteController;
use App\Http\Controllers\Category\EditController;
use App\Http\Controllers\Category\IndexController;
use App\Http\Controllers\Category\ShowController;
use App\Http\Controllers\Category\StoreController;
use App\Http\Controllers\Category\UpdateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['namespace' => 'App\Http\Controllers\Category', 'prefix' => 'categories'], function () {
    Route::get('/', IndexController::class)->name('categories.index');
    Route::get('/create', CreateController::class)->name('categories.create');
    Route::post('/', StoreController::class)->name('categories.store');
    Route::get('/{category}', ShowController::class)->name('categories.show');
    Route::get('/{category}/edit', EditController::class)->name('categories.edit');
    Route::patch('/{category}', UpdateController::class)->name('categories.update');
    Route::delete('/{category}', DeleteController::class)->name('categories.delete')->middleware('auth');
});

Route::group(['namespace' => 'App\Http\Controllers\Tag', 'prefix' => 'tags'], function () {
    Route::post('/', \App\Http\Controllers\Tag\StoreController::class)->name('tags.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
