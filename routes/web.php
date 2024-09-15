<?php

use App\Livewire\ListProducts;
use App\Livewire\ProductStatus;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/list-product', ListProducts::class)->name('list-product');
    Route::get('/product-status', ProductStatus::class)->name('product-status');
});