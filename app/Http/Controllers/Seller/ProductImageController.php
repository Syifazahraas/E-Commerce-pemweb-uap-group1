<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Show manage images page
     */
    public function manage($productId)
    {
        try {
            $product = Product::where('store_id', Auth::user()->store->id)
                ->with('productImages')
                ->findOrFail($productId);

            return view('seller.products.images', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error loading manage images: ' . $e->getMessage());
            return redirect()->route('seller.products.index')
                ->with('error', 'Produk tidak ditemukan.');
        }
    }

    /**
     * Upload multiple images saat create/edit product
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'images.required' => 'Minimal upload 1 gambar.',
            'images.min' => 'Minimal upload 1 gambar.',
            'images.max' => 'Maksimal upload 10 gambar.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar hanya JPG, JPEG, atau PNG.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB per file.',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::where('store_id', Auth::user()->store->id)
                ->findOrFail($productId);

            // Hitung jumlah gambar yang sudah ada
            $existingCount = $product->productImages()->count();
            $newCount = count($request->file('images'));

            if (($existingCount + $newCount) > 10) {
                return back()->with('error', 'Total gambar tidak boleh lebih dari 10.');
            }

            // Upload gambar baru
            $uploadedCount = 0;
            foreach ($request->file('images') as $index => $file) {
                if (!$file->isValid()) {
                    continue;
                }

                try {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $fileName, 'public');

                    if ($path) {
                        // Set thumbnail jika ini gambar pertama dan belum ada thumbnail
                        $isThumbnail = ($existingCount === 0 && $index === 0);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $path,
                            'is_thumbnail' => $isThumbnail,
                        ]);

                        $uploadedCount++;
                        Log::info('Image uploaded: ' . $path);
                    }
                } catch (\Exception $e) {
                    Log::error('Image upload error: ' . $e->getMessage());
                    continue;
                }
            }

            if ($uploadedCount === 0) {
                DB::rollBack();
                return back()->with('error', 'Gagal mengunggah gambar. Silakan coba lagi.');
            }

            DB::commit();
            return back()->with('success', $uploadedCount . ' gambar berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error uploading images: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat upload gambar.');
        }
    }

    /**
     * Set gambar sebagai thumbnail
     */
    public function setThumbnail($productId, $imageId)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('store_id', Auth::user()->store->id)
                ->findOrFail($productId);

            $image = ProductImage::where('product_id', $product->id)
                ->findOrFail($imageId);

            // Set semua gambar produk ini jadi bukan thumbnail
            ProductImage::where('product_id', $product->id)
                ->update(['is_thumbnail' => false]);

            // Set gambar ini sebagai thumbnail
            $image->is_thumbnail = true;
            $image->save();

            DB::commit();
            Log::info('Thumbnail changed for product: ' . $product->id . ', image: ' . $imageId);

            return redirect()->back()->with('success', 'Thumbnail berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting thumbnail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah thumbnail.');
        }
    }

    /**
     * Delete single image
     */
    public function destroy($productId, $imageId)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('store_id', Auth::user()->store->id)
                ->findOrFail($productId);

            $image = ProductImage::where('product_id', $product->id)
                ->findOrFail($imageId);


            // Cek apakah ini satu-satunya gambar
            $imageCount = ProductImage::where('product_id', $product->id)->count();
            if ($imageCount <= 1) {
                return redirect()->back()->with('error', 'Produk harus memiliki minimal 1 gambar.');
            }

            $wasThumbnail = $image->is_thumbnail;

            // Hapus file di storage
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            // Hapus record database
            $image->delete();

            // Jika yang dihapus adalah thumbnail, set gambar pertama sebagai thumbnail baru
            if ($wasThumbnail) {
                $firstImage = ProductImage::where('product_id', $product->id)->first();
                if ($firstImage) {
                    $firstImage->is_thumbnail = true;
                    $firstImage->save();
                }
            }

            DB::commit();
            Log::info('Product image deleted: ' . $imageId);
            return redirect()->back()->with('success', 'Foto produk berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus gambar.');
        }
    }
}
