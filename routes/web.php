<?php

use App\Http\Controllers\ProductController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('products',ProductController::class);

Route::middleware('auth')->group(function($router){
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    Route::middleware('is_admin')->group(function($router){
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products',[ProductController::class,'store'])->name('products.store');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    });
});
