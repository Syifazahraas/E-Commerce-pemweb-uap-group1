<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $store = Store::first();

            if (!$store) {
                $this->command->error('Store not found! Run StoreBalanceSeeder first.');
                return;
            }

            // Create categories with store_id
            $categories = [
                ['name' => 'Elektronik', 'slug' => 'elektronik'],
                ['name' => 'Fashion', 'slug' => 'fashion'],
                ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman'],
            ];

            $createdCategories = [];
            foreach ($categories as $categoryData) {

                // Tambahkan store_id disini
                $categoryData['store_id'] = $store->id;

                $category = ProductCategory::firstOrCreate(
                    [
                        'slug' => $categoryData['slug'],
                        'store_id' => $store->id,   // penting!
                    ],
                    $categoryData
                );

                $createdCategories[] = $category;
            }

            $this->command->info('✓ Categories created/found: ' . count($createdCategories));

            // Create products
            $products = [
                [
                    'name' => 'Laptop Gaming ASUS ROG',
                    'description' => 'Laptop gaming dengan spesifikasi tinggi, RAM 16GB, SSD 512GB, RTX 3060',
                    'price' => 15000000,
                    'stock' => 5,
                    'condition' => 'new',
                    'category_id' => $createdCategories[0]->id,
                ],
                [
                    'name' => 'Mouse Wireless Logitech',
                    'description' => 'Mouse wireless ergonomis dengan battery life hingga 2 tahun',
                    'price' => 250000,
                    'stock' => 20,
                    'condition' => 'new',
                    'category_id' => $createdCategories[0]->id,
                ],
                [
                    'name' => 'Keyboard Mechanical RGB',
                    'description' => 'Keyboard mechanical dengan RGB lighting, switch blue',
                    'price' => 450000,
                    'stock' => 15,
                    'condition' => 'new',
                    'category_id' => $createdCategories[0]->id,
                ],
                [
                    'name' => 'Webcam HD 1080p',
                    'description' => 'Webcam full HD dengan auto focus dan mic built-in',
                    'price' => 350000,
                    'stock' => 10,
                    'condition' => 'new',
                    'category_id' => $createdCategories[0]->id,
                ],
                [
                    'name' => 'T-Shirt Premium Cotton',
                    'description' => 'Kaos premium 100% cotton, nyaman dipakai',
                    'price' => 150000,
                    'stock' => 50,
                    'condition' => 'new',
                    'category_id' => $createdCategories[1]->id,
                ],
            ];

            foreach ($products as $productData) {
                $slug = Str::slug($productData['name']);

                Product::firstOrCreate(
                    [
                        'slug' => $slug,
                        'store_id' => $store->id,
                    ],
                    [
                        'name' => $productData['name'],
                        'description' => $productData['description'],
                        'price' => $productData['price'],
                        'stock' => $productData['stock'],
                        'condition' => $productData['condition'],
                        'product_category_id' => $productData['category_id'],
                        'weight' => 1000,
                    ]
                );
            }

            $this->command->info('✓ Products created: ' . count($products));
            $this->command->info('');
            $this->command->info('Products ready for transactions!');
        });
    }
}
