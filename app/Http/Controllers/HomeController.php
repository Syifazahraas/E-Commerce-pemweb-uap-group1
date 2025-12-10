<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Ambil kategori parent (yang tidak punya parent_id)
            $categories = ProductCategory::whereNull('parent_id')
                ->with(['children' => function($query) {
                    $query->withCount('products');
                }])
                ->withCount('products')
                ->take(6)
                ->get();

            // Ambil produk terbaru dengan gambar dan kategori
            $featuredProducts = Product::with(['productImages' => function($query) {
                    $query->where('is_thumbnail', true);
                }, 'productCategory', 'store'])
                ->where('stock', '>', 0)
                ->latest()
                ->take(8)
                ->get();

            // Ambil produk populer (berdasarkan stok terjual atau bisa custom logic)
            $popularProducts = Product::with(['productImages' => function($query) {
                    $query->where('is_thumbnail', true);
                }, 'productCategory', 'store'])
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->take(8)
                ->get();

            return view('welcome', compact('categories', 'featuredProducts', 'popularProducts'));

        } catch (\Exception $e) {
            Log::error('Error loading homepage: ' . $e->getMessage());

            // Fallback jika error
            return view('welcome', [
                'categories' => collect([]),
                'featuredProducts' => collect([]),
                'popularProducts' => collect([])
            ]);
        }
    }
}
