<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductCategoryController;
use App\Http\Controllers\Seller\ProductImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PUBLIC (Buyer) Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [BuyerController::class, 'home'])->name('home');

Route::get('/product/{id}', [BuyerController::class, 'productDetail'])->name('product.show');
Route::get('/cart', [BuyerController::class, 'cart'])->name('cart');
Route::post('/cart/add', [BuyerController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/{id}', [BuyerController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/{id}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/api/search', [BuyerController::class, 'searchProducts'])->name('api.search');

Route::get('/sale', [BuyerController::class, 'sale'])->name('sale');
Route::get('/products', [BuyerController::class, 'products'])->name('products.index');

/*
|--------------------------------------------------------------------------
| Buyer – Auth Required Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [BuyerController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/my-orders', [BuyerController::class, 'transactionHistory'])->name('transaction.history');
    Route::get('/my-orders/{id}', [BuyerController::class, 'transactionDetail'])->name('transaction.detail');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirector (Admin / Seller / Buyer)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.stores.index');
    } elseif ($user->role === 'seller') {
        return redirect()->route('seller.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/reviews', [\App\Http\Controllers\ProductReviewController::class, 'store'])
        ->name('reviews.store');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // STORES
        Route::resource('stores', AdminStoreController::class);
        Route::get('stores/{store}/approve', [AdminStoreController::class, 'approve'])
            ->name('stores.approve');

        // USERS
        Route::resource('users', UserController::class, ['only' => ['index', 'show', 'destroy']]);
        Route::post('users/{user}/toggle-role', [UserController::class, 'toggleRole'])
            ->name('users.toggleRole');
    });

/*
|--------------------------------------------------------------------------
| SELLER – Create Store (No Verification Needed)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/store/create', [SellerStoreController::class, 'create'])->name('store.create');
        Route::post('/store', [SellerStoreController::class, 'store'])->name('store.store');
    });

/*
|--------------------------------------------------------------------------
| SELLER – Verified Seller Only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'seller.access'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [SellerStoreController::class, 'dashboard'])
            ->name('dashboard');

        // STORE
        Route::get('/store/edit', [SellerStoreController::class, 'edit'])->name('store.edit');
        Route::put('/store', [SellerStoreController::class, 'update'])->name('store.update');
        Route::delete('/store', [SellerStoreController::class, 'destroy'])->name('store.destroy');

        // CATEGORY
        Route::resource('category', ProductCategoryController::class);

        // PRODUCTS
        Route::resource('products', ProductController::class);

        // PRODUCT IMAGES
        Route::prefix('products/{product}/images')->name('products.images.')->group(function () {
            Route::get('/', [ProductImageController::class, 'manage'])->name('manage');
            Route::post('/', [ProductImageController::class, 'store'])->name('store');
            Route::post('/{imageId}/thumbnail', [ProductImageController::class, 'setThumbnail'])->name('thumbnail');
            Route::delete('/{imageId}', [ProductImageController::class, 'destroy'])->name('destroy');
        });

        // BALANCE
        Route::get('/balance', [\App\Http\Controllers\Seller\BalanceController::class, 'index'])
            ->name('balance.index');

        // WITHDRAWALS
        Route::get('/withdrawals', [\App\Http\Controllers\Seller\WithdrawalController::class, 'index'])
            ->name('withdrawals.index');
        Route::get('/withdrawals/create', [\App\Http\Controllers\Seller\WithdrawalController::class, 'create'])
            ->name('withdrawals.create');
        Route::post('/withdrawals', [\App\Http\Controllers\Seller\WithdrawalController::class, 'store'])
            ->name('withdrawals.store');
        Route::post('/withdrawals/bank-account', [\App\Http\Controllers\Seller\WithdrawalController::class, 'updateBankAccount'])
            ->name('withdrawals.updateBankAccount');

        // ORDERS
        Route::get('/orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Seller\OrderController::class, 'show'])
            ->name('orders.show');
        Route::post('/orders/{id}/tracking', [\App\Http\Controllers\Seller\OrderController::class, 'updateTracking'])
            ->name('orders.updateTracking');
        Route::post('/orders/{id}/shipping', [\App\Http\Controllers\Seller\OrderController::class, 'updateShipping'])
            ->name('orders.updateShipping');
    });
