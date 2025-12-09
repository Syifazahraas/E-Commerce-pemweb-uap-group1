<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreController;

use App\Http\Controllers\BuyerController;

Route::get('/', [BuyerController::class, 'home'])->name('home');

// Buyer/Customer routes
Route::get('/product/{id}', [BuyerController::class, 'productDetail'])->name('product.show');
Route::get('/cart', [BuyerController::class, 'cart'])->name('cart');
Route::post('/cart/add', [BuyerController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/{id}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');

// Live Search API
Route::get('/api/search', [BuyerController::class, 'searchProducts'])->name('api.search');

// Sale Products Page
Route::get('/sale', [BuyerController::class, 'sale'])->name('sale');

// Product Catalog Page
Route::get('/products', [BuyerController::class, 'products'])->name('products.index');


Route::middleware('auth')->group(function () {
    Route::get('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [BuyerController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/my-orders', [BuyerController::class, 'transactionHistory'])->name('transaction.history');
    Route::get('/my-orders/{id}', [BuyerController::class, 'transactionDetail'])->name('transaction.detail');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Redirect based on role
    if ($user->role === 'admin') {
        return redirect()->route('admin.stores.index');
    } elseif ($user->isSeller()) {
        return redirect()->route('seller.dashboard');
    } else {
        // Buyer/Member - redirect to homepage
        return redirect()->route('home');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Disable delete for now if not needed
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Seller\StoreController::class, 'dashboard'])
        ->name('dashboard');

    // ROUTE STORE
    Route::get('/store/create', [\App\Http\Controllers\Seller\StoreController::class, 'create'])
        ->name('store.create');
    Route::post('/store', [\App\Http\Controllers\Seller\StoreController::class, 'store'])
        ->name('store.store');

    Route::get('/store/edit', [\App\Http\Controllers\Seller\StoreController::class, 'edit'])
        ->name('store.edit');
    Route::post('/store/update', [\App\Http\Controllers\Seller\StoreController::class, 'update'])
        ->name('store.update');

         Route::get('/products', function () {
        return "HALAMAN PRODUCT INDEX SEMENTARA";
    })->name('products.index');

    Route::get('/orders', function () {
        return "HALAMAN PESANAN SEMENTARA";
    })->name('orders.index');

    Route::get('/balance', function () {
        return "HALAMAN SALDO SEMENTARA";
    })->name('balance.index');

    Route::get('/withdrawals', function () {
        return "HALAMAN PENARIKAN DANA SEMENTARA";
    })->name('withdrawals.index');
});


Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Store Management
        Route::resource('stores', StoreController::class);

        // Route approve harus DI LUAR resource
        Route::get('stores/{store}/approve', [StoreController::class, 'approve'])->name('stores.approve');

        Route::get('/users', function () {
            return "HALAMAN PENGGUNA SEMENTARA";
        })->name('users.index');

        Route::get('/orders', function () {
            return "HALAMAN PESANAN ADMIN SEMENTARA";
        })->name('orders.index');
});

