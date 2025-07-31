<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CostumerController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::middleware('auth')->group(function () {});

Route::resource('costumer', CostumerController::class);
Route::resource('product', ProductController::class);
Route::resource('sale', SaleController::class);
Route::get('sale/{sale}/pdf', [SaleController::class, 'pdf'])->name('sale.pdf');
