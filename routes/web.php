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
    Route::get('/create', CreateController::class)->name('categories.create')->middleware('auth');
    Route::post('/', StoreController::class)->name('categories.store')->middleware('auth');
    Route::get('/{category}', ShowController::class)->name('categories.show');
    Route::get('/{category}/edit', EditController::class)->name('categories.edit')->middleware('auth');
    Route::patch('/{category}', UpdateController::class)->name('categories.update')->middleware('auth');
    Route::delete('/{category}', DeleteController::class)->name('categories.delete')->middleware('auth');
});

Route::group(['namespace' => 'App\Http\Controllers\Tag', 'prefix' => 'tags'], function () {
    Route::get('/', \App\Http\Controllers\Tag\IndexController::class)->name('tags.index');
    Route::get('/create', \App\Http\Controllers\Tag\CreateController::class)->name('tags.create')->middleware('auth');
    Route::post('/', \App\Http\Controllers\Tag\StoreController::class)->name('tags.store')->middleware('auth');
    Route::get('/{tag}', \App\Http\Controllers\Tag\ShowController::class)->name('tags.show');
    Route::get('/{tag}/edit', \App\Http\Controllers\Tag\EditController::class)->name('tags.edit')->middleware('auth');
    Route::patch('/{tag}', \App\Http\Controllers\Tag\UpdateController::class)->name('tags.update')->middleware('auth');
    Route::delete('/{tag}', \App\Http\Controllers\Tag\DeleteController::class)->name('tags.delete')->middleware('auth');
});

Route::group(['namespace' => 'App\Http\Controllers\Product', 'prefix' => 'products'], function () {
    Route::get('/', \App\Http\Controllers\Product\IndexController::class)->name('products.index');
    Route::get('/create', \App\Http\Controllers\Product\CreateController::class)->name('products.create');
    Route::post('/', \App\Http\Controllers\Product\StoreController::class)->name('products.store');
    Route::get('/{product}', \App\Http\Controllers\Product\ShowController::class)->name('products.show');
    Route::get('/{product}/edit', \App\Http\Controllers\Product\EditController::class)->name('products.edit');
    Route::patch('/{product}', \App\Http\Controllers\Product\UpdateController::class)->name('products.update');
    Route::delete('/{product}', \App\Http\Controllers\Product\DeleteController::class)->name('products.delete')->middleware('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
