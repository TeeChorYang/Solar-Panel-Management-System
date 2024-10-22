<?php

use App\Livewire\ListInstallations;
use App\Livewire\ListMaintenances;
use App\Livewire\ListProducts;
use App\Livewire\ProductStatus;
use App\Livewire\CustomerEditReview;
use App\Livewire\CustomerListOrders;
use App\Livewire\CustomerListReviews;
use App\Livewire\CustomerProductGrid;
use Illuminate\Support\Facades\Route;
use App\Livewire\CustomerEditOrderRequest;
use App\Livewire\CustomerListInstallations;
use App\Livewire\CustomerListOrderRequests;
use App\Livewire\CustomerViewOrderInfoList;
use App\Livewire\CustomerLeaveProductReview;
use App\Livewire\CustomerMakeOrderRequestInfoList;
use App\Livewire\CustomerViewInstallationInfoList;
use App\Livewire\CustomerViewOrderRequestInfoList;
use App\Livewire\ManagerOrderStatus;
use App\Livewire\Support;

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

    // Customer routes start here
    Route::prefix('products')->group(function () {
        Route::get('/list', CustomerProductGrid::class)->name('customer-product-grid');
        Route::get('/view/{product}', CustomerMakeOrderRequestInfoList::class)->name('product-view');
    });

    Route::prefix('order-requests')->group(function () {
        Route::get('/list', CustomerListOrderRequests::class)->name('customer-list-order-requests');
        Route::get('/edit/{orderRequest}', CustomerEditOrderRequest::class)->name('edit-order-request');
        Route::get('/view/{orderRequest}', CustomerViewOrderRequestInfoList::class)->name('view-order-request');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/list', CustomerListOrders::class)->name('customer-list-orders');
        Route::get('/view/{order}', CustomerViewOrderInfoList::class)->name('order-view');
        Route::get('/review/{order}', CustomerLeaveProductReview::class)->name('leave-product-review');
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/list', CustomerListReviews::class)->name('customer-list-reviews');
        Route::get('/edit/{review}', CustomerEditReview::class)->name('edit-review');
    });

    Route::prefix('installations')->group(function () {
        Route::get('/list', CustomerListInstallations::class)->name('customer-list-installations');
        Route::get('/view/{installation}', CustomerViewInstallationInfoList::class)->name('installation-view');
    });
    // Customer routes end here

    Route::get('/list-product', ListProducts::class)->name('list-product');
    Route::get('/product-status', ProductStatus::class)->name('product-status');
    Route::get('/manager-order-status', ManagerOrderStatus::class)->name('manager-order-status');
    Route::get('/list-installations', ListInstallations::class)->name('list-installations');
    Route::get('/list-maintenances', ListMaintenances::class)->name('list-maintenances');
    Route::get('/support', Support::class)->name('support');
});
