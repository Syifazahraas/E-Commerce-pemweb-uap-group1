<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storeId = Auth::user()->store->id;

        // khusus kategori milik store ini
        $categories = ProductCategory::where('store_id', $storeId)
            ->withCount('products')
            ->latest()
            ->paginate(10);

        return view('seller.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $storeId = Auth::user()->store->id;

        // parent kategori hanya dari store yang sama
        $parents = ProductCategory::where('store_id', $storeId)->get();

        return view('seller.category.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'parent_id'   => 'nullable|exists:product_categories,id',
            'tagline'     => 'nullable|string',
            'description' => 'nullable|string',
            'icon'        => 'nullable|image|max:2048',
        ]);

        $storeId = Auth::user()->store->id;


        ProductCategory::create([
            'store_id'   => $storeId,
            'parent_id'  => $request->parent_id,
            'name'       => $request->name,
            'slug'       => Str::slug($request->name),
            'tagline'    => $request->tagline,
            'description'=> $request->description,
        ]);

        return redirect()->route('seller.category.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $category)
    {
        $storeId = Auth::user()->store->id;

        // hanya ambil parent kategori dari store sama dan bukan dirinya sendiri
        $parents = ProductCategory::where('store_id', $storeId)
            ->where('id', '!=', $category->id)
            ->get();

        return view('seller.category.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'parent_id'   => 'nullable|exists:product_categories,id',
            'tagline'     => 'nullable|string',
            'description' => 'nullable|string',
            'icon'        => 'nullable|image|max:2048',
        ]);


        $category->update([
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'tagline'     => $request->tagline,
            'description' => $request->description,

        ]);

        return redirect()->route('seller.category.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $category)
    {
        $category->delete();

        return redirect()->route('seller.category.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
