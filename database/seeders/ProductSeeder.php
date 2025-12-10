<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $store = Store::first();
            if (!$store) {
                $this->command->error('Store not found! Run StoreBalanceSeeder / SellerSeeder first.');
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 1. CREATE CATEGORIES — MERGED FROM BOTH BRANCHES
            |--------------------------------------------------------------------------
            */

            // Categories from HEAD (require store_id)
            $categoriesWithStore = [
                ['name' => 'Elektronik', 'slug' => 'elektronik'],
                ['name' => 'Fashion', 'slug' => 'fashion'],
                ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman'],
            ];

            $createdCategories = [];

            foreach ($categoriesWithStore as $cat) {
                $cat['store_id'] = $store->id;

                $category = ProductCategory::firstOrCreate(
                    [
                        'slug' => $cat['slug'],
                        'store_id' => $store->id
                    ],
                    $cat
                );

                $createdCategories[$cat['name']] = $category->id;
            }

            $this->command->info("✓ Categories with store_id created: " . count($categoriesWithStore));


            /*
            |--------------------------------------------------------------------------
            | FIXED — Categories without store_id (global categories)
            | Sekarang ditambahkan store_id agar tidak error.
            |--------------------------------------------------------------------------
            */

            $globalCategories = [
                ['name' => 'Sneakers', 'description' => 'Casual and stylish sneakers'],
                ['name' => 'Running Shoes', 'description' => 'Performance running shoes'],
                ['name' => 'Loafers', 'description' => 'Comfortable slip-on shoes'],
                ['name' => 'Boots', 'description' => 'Durable boots'],
                ['name' => 'Sandals', 'description' => 'Lightweight sandals'],
            ];

            foreach ($globalCategories as $gc) {

                $category = ProductCategory::updateOrCreate(
                    [
                        'slug' => Str::slug($gc['name']),
                        'store_id' => $store->id, // FIX WAJIB
                    ],
                    [
                        'name' => $gc['name'],
                        'description' => $gc['description'],
                        'store_id' => $store->id, // FIX WAJIB
                    ]
                );

                $createdCategories[$gc['name']] = $category->id;
            }

            $this->command->info("✓ Global shoe categories created: " . count($globalCategories));


            /*
            |--------------------------------------------------------------------------
            | 2. CREATE PRODUCTS — MERGED FROM BOTH BRANCHES
            |--------------------------------------------------------------------------
            */

            // Products from HEAD
            $productsHead = [
                [
                    'name' => 'Laptop Gaming ASUS ROG',
                    'description' => 'Laptop gaming RAM 16GB SSD 512GB RTX 3060',
                    'price' => 15000000,
                    'stock' => 5,
                    'condition' => 'new',
                    'category' => 'Elektronik'
                ],
                [
                    'name' => 'Mouse Wireless Logitech',
                    'description' => 'Mouse wireless ergonomis',
                    'price' => 250000,
                    'stock' => 20,
                    'condition' => 'new',
                    'category' => 'Elektronik',
                ],
                [
                    'name' => 'Keyboard Mechanical RGB',
                    'description' => 'Keyboard mechanical RGB switch blue',
                    'price' => 450000,
                    'stock' => 15,
                    'condition' => 'new',
                    'category' => 'Elektronik',
                ],
                [
                    'name' => 'Webcam HD 1080p',
                    'description' => 'Webcam full HD auto focus',
                    'price' => 350000,
                    'stock' => 10,
                    'condition' => 'new',
                    'category' => 'Elektronik',
                ],
                [
                    'name' => 'T-Shirt Premium Cotton',
                    'description' => 'Kaos premium 100% cotton',
                    'price' => 150000,
                    'stock' => 50,
                    'condition' => 'new',
                    'category' => 'Fashion',
                ],
            ];

            foreach ($productsHead as $p) {

                Product::firstOrCreate(
                    [
                        'slug' => Str::slug($p['name']),
                        'store_id' => $store->id,
                    ],
                    [
                        'name' => $p['name'],
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'stock' => $p['stock'],
                        'condition' => $p['condition'],
                        'product_category_id' => $createdCategories[$p['category']],
                        'weight' => 1000,
                    ]
                );
            }

            $this->command->info("✓ Products from HEAD created: " . count($productsHead));


            // Products from ORIGIN/USER-SYIFA
            $productsShoes = [
                ['name' => 'Air Max Classic White', 'description' => 'White sneakers', 'price' => 850000, 'stock' => 15, 'category' => 'Sneakers'],
                ['name' => 'Urban Black Sneakers', 'description' => 'Black sneakers urban style', 'price' => 650000, 'stock' => 20, 'category' => 'Sneakers'],
                ['name' => 'Retro Blue Runners', 'description' => 'Vintage blue runners', 'price' => 750000, 'stock' => 12, 'category' => 'Sneakers'],
                ['name' => 'Pro Runner 3000', 'description' => 'Professional running shoes', 'price' => 1200000, 'stock' => 10, 'category' => 'Running Shoes'],
                ['name' => 'Speed Boost Elite', 'description' => 'High performance running shoes', 'price' => 1500000, 'stock' => 8, 'category' => 'Running Shoes'],
                ['name' => 'Classic Brown Loafers', 'description' => 'Brown loafers', 'price' => 900000, 'stock' => 18, 'category' => 'Loafers'],
                ['name' => 'Navy Suede Loafers', 'description' => 'Navy suede loafers', 'price' => 950000, 'stock' => 14, 'category' => 'Loafers'],
                ['name' => 'Mountain Hiker Boots', 'description' => 'Hiking boots', 'price' => 1100000, 'stock' => 12, 'category' => 'Boots'],
                ['name' => 'Chelsea Ankle Boots', 'description' => 'Chelsea boots', 'price' => 880000, 'stock' => 16, 'category' => 'Boots'],
                ['name' => 'Beach Sandals', 'description' => 'Beach sandals', 'price' => 250000, 'stock' => 30, 'category' => 'Sandals'],
                ['name' => 'Sport Slide Sandals', 'description' => 'Sport sandals', 'price' => 350000, 'stock' => 25, 'category' => 'Sandals'],
            ];

            foreach ($productsShoes as $p) {

                Product::updateOrCreate(
                    [
                        'slug' => Str::slug($p['name']),
                    ],
                    [
                        'name' => $p['name'],
                        'store_id' => $store->id,
                        'product_category_id' => $createdCategories[$p['category']],
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'stock' => $p['stock'],
                        'condition' => 'new',
                        'weight' => 500,
                        'material' => 'Premium Synthetic Leather',
                        'sizes' => ["39", "40", "41", "42", "43", "44"],
                    ]
                );
            }

            $this->command->info("✓ Shoe products created: " . count($productsShoes));
            $this->command->info("All product seeding complete!");
        });
    }
}
