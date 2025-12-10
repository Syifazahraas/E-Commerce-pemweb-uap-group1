<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories first
        $categories = [
            ['name' => 'Sneakers', 'description' => 'Casual and stylish sneakers'],
            ['name' => 'Running Shoes', 'description' => 'Performance running shoes'],
            ['name' => 'Loafers', 'description' => 'Comfortable slip-on shoes'],
            ['name' => 'Boots', 'description' => 'Durable boots'],
            ['name' => 'Sandals', 'description' => 'Lightweight sandals'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }

        // Get the seller's store
        $store = Store::first();
        if (!$store) {
            $this->command->warn('No store found. Please run SellerSeeder first.');
            return;
        }

        // Sample products
        $products = [
            ['name' => 'New Balance 530 Unisex Sneakers', 'description' => 'Classic retro style meets modern comfort in the New Balance 530.', 'price' => 1499000, 'stock' => 15, 'category' => 'Sneakers'],
            ['name' => 'Classic Clog Kids Bone', 'description' => 'Comfortable and lightweight clogs for kids, perfect for everyday wear.', 'price' => 599000, 'stock' => 20, 'category' => 'Sandals'],
            ['name' => 'Handball Spezial Mens Sneakers', 'description' => 'Vintage handball design updated for the streets.', 'price' => 1700000, 'stock' => 12, 'category' => 'Sneakers'],
            ['name' => 'Cortez Mens Sneakers', 'description' => 'The original running shoe, now a street style icon.', 'price' => 1299000, 'stock' => 10, 'category' => 'Sneakers'],
            ['name' => 'Speed Boost Elite', 'description' => 'High-performance running shoes designed for maximum speed and energy return.', 'price' => 1500000, 'stock' => 8, 'category' => 'Running Shoes'],
            ['name' => 'Air Jordan 1 Retro Low OG', 'description' => 'Premium materials and iconic design in a low-top silhouette.', 'price' => 2499000, 'stock' => 5, 'category' => 'Sneakers'],
            ['name' => 'Sunray Protect 4 Boys Sandals', 'description' => 'Protective and quick-drying sandals for active kids.', 'price' => 499000, 'stock' => 18, 'category' => 'Sandals'],
            ['name' => 'Cloud 6 Mens Sneakers', 'description' => 'Ultra-lightweight running shoes with cloud-like cushioning.', 'price' => 2100000, 'stock' => 14, 'category' => 'Running Shoes'],
            ['name' => 'Adizero Evo SL Womens Running Shoes', 'description' => 'Top-tier racing shoes for breaking personal records.', 'price' => 2800000, 'stock' => 7, 'category' => 'Running Shoes'],
            ['name' => 'Skate Loafer Mens Sneakers', 'description' => 'A unique blend of skate durability and loafer style.', 'price' => 1100000, 'stock' => 16, 'category' => 'Loafers'],
            ['name' => 'Floody Blue Leather Boots', 'description' => 'Rugged leather boots with a stylish blue finish.', 'price' => 1850000, 'stock' => 12, 'category' => 'Boots'],
        ];

        foreach ($products as $p) {
            $category = ProductCategory::where('slug', Str::slug($p['category']))->first();
            
            if ($category) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($p['name'])],
                    [
                        'name' => $p['name'],
                        'store_id' => $store->id,
                        'product_category_id' => $category->id,
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'stock' => $p['stock'],
                        'condition' => 'new',
                        'weight' => 500,
                        'material' => 'Premium Synthetic Leather', // Default sample material
                        'sizes' => ["39", "40", "41", "42", "43", "44"], // Default sample sizes
                    ]
                );
            }
        }

        $this->command->info('✓ Products seeded successfully!');
    }
}
