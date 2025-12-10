<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Gambar sepatu menggunakan placeholder yang reliable
        $productImages = [
            // New Balance 530 - retro/vintage sneaker style
            'new-balance-530-unisex-sneakers' => [
                'https://images.unsplash.com/photo-1539185441755-769473a23570?w=800&h=1000&fit=crop&q=80',
            ],
            // Crocs Classic Clog - beige/bone colored clogs
            'classic-clog-kids-bone' => [
                'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=800&h=1000&fit=crop&q=80',
            ],
            // Adidas Handball Spezial - blue retro sneakers
            'handball-spezial-mens-sneakers' => [
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=1000&fit=crop&q=80',
            ],
            // Nike Cortez - classic white/red Nike sneakers
            'cortez-mens-sneakers' => [
                'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=800&h=1000&fit=crop&q=80',
            ],
            // Speed Boost Elite - performance running shoes
            'speed-boost-elite' => [
                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=1000&fit=crop&q=80',
            ],
            // Air Jordan 1 Low - red/black Jordan sneakers
            'air-jordan-1-retro-low-og' => [
                'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=800&h=1000&fit=crop&q=80',
            ],
            // Nike Sunray - kids pink/white sport sandals
            'sunray-protect-4-boys-sandals' => [
                'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=800&h=1000&fit=crop&q=80',
            ],
            // On Cloud 6 - modern running sneakers with cloud technology
            'cloud-6-mens-sneakers' => [
                'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=800&h=1000&fit=crop&q=80',
            ],
            // Adidas Adizero - pink/purple women's racing shoes
            'adizero-evo-sl-womens-running-shoes' => [
                'https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?w=800&h=1000&fit=crop&q=80',
            ],
            // Vans Skate Loafer - dark green/forest Vans style sneakers
            'skate-loafer-mens-sneakers' => [
                'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=800&h=1000&fit=crop&q=80',
            ],
            // Floody Blue Boots - blue/brown leather boots
            'floody-blue-leather-boots' => [
                'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=800&h=1000&fit=crop&q=80',
            ],
        ];

        foreach ($productImages as $slug => $images) {
            $product = Product::where('slug', $slug)->first();
            
            if ($product) {
                // Clear existing images to avoid duplicate/stale placeholders
                $product->images()->delete();
                
                foreach ($images as $imageUrl) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imageUrl
                    ]);
                }
            }
        }

        $this->command->info('✓ Product images added successfully!');
    }
}
