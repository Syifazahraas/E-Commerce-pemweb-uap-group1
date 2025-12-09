<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('user')->latest()->paginate(10);
        return view('admin.stores.index', compact('stores'));
    }

    public function show(Store $store)
    {
        $store->load('user');
        return view('admin.stores.show', compact('store'));
    }

    public function approve(Store $store)
    {
        $store->is_verified = 1;
        $store->save();

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil disetujui.');
    }

    /**
     * Update logo atau data store dari admin
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $store->name = $validated['name'];

        // Upload logo BARU (replace if exists)
        if ($request->hasFile('logo')) {

            // Hapus logo lama
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }

            // Upload gambar baru mirip ProductController
            $file = $request->file('logo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('store_logos', $fileName, 'public');

            $store->logo = $path;
        }

        $store->save();

        return redirect()->route('admin.stores.show', $store->id)
            ->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Store $store)
    {
        // Hapus logo jika ada
        if ($store->logo && Storage::disk('public')->exists($store->logo)) {
            Storage::disk('public')->delete($store->logo);
        }

        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil dihapus.');
    }
}
