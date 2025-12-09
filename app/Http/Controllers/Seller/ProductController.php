<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        try {
            $store = Auth::user()->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                                 ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            $products = Product::where('store_id', $store->id)
                              ->with('productCategory', 'productImages')
                              ->latest()
                              ->paginate(15);

            return view('seller.products.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error Products Index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat produk.');
        }
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        try {
            $store = Auth::user()->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                                 ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            $categories = ProductCategory::whereNull('parent_id')->with('children')->get();
            return view('seller.products.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error Load Create Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka form.');
        }
    }

    /**
     * Store a newly created product in storage
     * Note: Image upload handled by ProductImageController
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,second',
            'description' => 'nullable|string|max:5000',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'product_category_id.required' => 'Kategori wajib dipilih.',
            'product_category_id.exists' => 'Kategori tidak valid.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'condition.required' => 'Kondisi wajib dipilih.',
            'condition.in' => 'Kondisi harus Baru atau Bekas.',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user || !$user->store) {
                return redirect()->route('seller.store.create')
                                 ->with('error', 'Silakan buat toko terlebih dahulu.');
            }

            $store = $user->store;

            // Create product
            $product = new Product();
            $product->store_id = $store->id;
            $product->product_category_id = $validated['product_category_id'];
            $product->name = $validated['name'];
            $product->slug = Str::slug($validated['name']) . '-' . Str::random(6);
            $product->price = $validated['price'];
            $product->stock = $validated['stock'];
            $product->weight = $validated['weight'] ?? 0;
            $product->condition = $validated['condition'];
            $product->description = $validated['description'] ?? null;

            if (!$product->save()) {
                DB::rollBack();
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'Gagal menyimpan produk. Silakan coba lagi.');
            }

            Log::info('Product created: ' . $product->id);

            DB::commit();

            // Redirect ke halaman upload gambar atau langsung ke edit dengan session
            return redirect()->route('seller.products.edit', $product->id)
                             ->with('success', 'Produk berhasil ditambahkan. Silakan upload gambar produk.')
                             ->with('new_product', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error: ' . json_encode($e->errors()));
            return redirect()->back()
                             ->withInput()
                             ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Store Product: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        try {
            $store = Auth::user()->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                                 ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            $product = Product::where('store_id', $store->id)
                             ->with('productImages', 'productCategory')
                             ->findOrFail($id);

            return view('seller.products.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error Show Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        try {
            $store = Auth::user()->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                                 ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            $product = Product::where('store_id', $store->id)
                             ->with('productImages', 'productCategory')
                             ->findOrFail($id);

            $categories = ProductCategory::whereNull('parent_id')->with('children')->get();

            return view('seller.products.edit', compact('product', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error Load Edit Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka form edit.');
        }
    }

    /**
     * Update the specified product in storage
     * Note: Image upload handled by ProductImageController
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,second',
            'description' => 'nullable|string|max:5000',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'product_category_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga wajib diisi.',
            'stock.required' => 'Stok wajib diisi.',
            'condition.required' => 'Kondisi wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user || !$user->store) {
                return redirect()->route('seller.store.create')
                                 ->with('error', 'Silakan buat toko terlebih dahulu.');
            }

            $store = $user->store;
            $product = Product::where('store_id', $store->id)->findOrFail($id);

            // Update product data
            $product->product_category_id = $validated['product_category_id'];
            $product->name = $validated['name'];
            $product->price = $validated['price'];
            $product->stock = $validated['stock'];
            $product->weight = $validated['weight'] ?? 0;
            $product->condition = $validated['condition'];
            $product->description = $validated['description'] ?? null;

            if (!$product->save()) {
                DB::rollBack();
                return redirect()->back()
                                 ->withInput()
                                 ->with('error', 'Gagal memperbarui produk.');
            }

            Log::info('Product updated: ' . $product->id);

            DB::commit();
            return redirect()->route('seller.products.edit', $product->id)
                             ->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Update Product: ' . $e->getMessage());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if (!$user || !$user->store) {
                return redirect()->route('seller.store.create')
                                 ->with('error', 'Silakan buat toko terlebih dahulu.');
            }

            $store = $user->store;
            $product = Product::where('store_id', $store->id)->findOrFail($id);

            // Delete images from storage
            foreach ($product->productImages as $img) {
                try {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                    $img->delete();
                } catch (\Exception $e) {
                    Log::error('Error deleting image file: ' . $e->getMessage());
                }
            }

            // Delete product
            $product->delete();

            Log::info('Product deleted: ' . $id);

            DB::commit();
            return redirect()->route('seller.products.index')
                             ->with('success', 'Produk berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Delete Product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus produk.');
        }
    }
}
