<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductCategoryController;
use App\Http\Controllers\Seller\ProductImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'admin.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Store Management
        Route::resource('stores', StoreController::class);

        // Route approve harus DI LUAR resource
        Route::get('stores/{store}/approve', [StoreController::class, 'approve'])->name('stores.approve');

        // Routes Users
        Route::resource('users', UserController::class, [ 'only' => ['index', 'show', 'destroy'] ]);
        Route::post('users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggleRole');
});


Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
        Route::get('/store/create', [\App\Http\Controllers\Seller\StoreController::class, 'create'])->name('store.create');
        Route::post('/store', [\App\Http\Controllers\Seller\StoreController::class, 'store'])->name('store.store');
    });

Route::middleware(['auth', 'seller.access'])->prefix('seller')->name('seller.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Seller\StoreController::class, 'dashboard'])
        ->name('dashboard');

    // ROUTE STORE
    Route::get('/store/edit', [\App\Http\Controllers\Seller\StoreController::class, 'edit'])->name('store.edit');
    Route::put('/store', [\App\Http\Controllers\Seller\StoreController::class, 'update'])->name('store.update');
    Route::delete('/store', [\App\Http\Controllers\Seller\StoreController::class, 'destroy'])->name('store.destroy');

    // ROUTE CATEGORY
    Route::resource('category', ProductCategoryController::class);

    // ROUTE PRODUCTS
    Route::resource('products', ProductController::class);
    // Product Image routes
    Route::prefix('products/{product}/images')->name('products.images.')->group(function () {
        Route::get('/', [ProductImageController::class, 'manage'])->name('manage');
        Route::post('/', [ProductImageController::class, 'store'])->name('store');
        Route::post('/{imageId}/thumbnail', [ProductImageController::class, 'setThumbnail'])->name('thumbnail');
        Route::delete('/{imageId}', [ProductImageController::class, 'destroy'])->name('destroy');
    });

    // ROUTE BALANCE
    Route::get('/balance', [App\Http\Controllers\Seller\BalanceController::class, 'index'])
        ->name('balance.index');

        // ROUTE WITHDRAWAL
    Route::get('/withdrawals', [App\Http\Controllers\Seller\WithdrawalController::class, 'index'])
        ->name('withdrawals.index');
    Route::get('/withdrawals/create', [App\Http\Controllers\Seller\WithdrawalController::class, 'create'])
        ->name('withdrawals.create');
    Route::post('/withdrawals', [App\Http\Controllers\Seller\WithdrawalController::class, 'store'])
        ->name('withdrawals.store');
    Route::post('/withdrawals/bank-account', [App\Http\Controllers\Seller\WithdrawalController::class, 'updateBankAccount'])
        ->name('withdrawals.updateBankAccount');

        // ROUTE ORDERS
    Route::get('/orders', [App\Http\Controllers\Seller\OrderController::class, 'index'])
        ->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Seller\OrderController::class, 'show'])
        ->name('orders.show');
    Route::post('/orders/{id}/tracking', [App\Http\Controllers\Seller\OrderController::class, 'updateTracking'])
        ->name('orders.updateTracking');
    Route::post('/orders/{id}/shipping', [App\Http\Controllers\Seller\OrderController::class, 'updateShipping'])
        ->name('orders.updateShipping');
});
