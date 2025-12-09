<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function dashboard()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $store = $user->store;

        if (!$store) {
            return redirect()->route('seller.store.create')
                             ->with('warning', 'Silakan buat toko terlebih dahulu.');
        }

        if (!$store->is_verified) {
            return redirect()->route('seller.store.create')
                             ->with('warning', 'Toko Anda sedang menunggu verifikasi admin.');
        }

        $totalProducts = $store->products()->count();

        // Count orders
        $totalOrders = \App\Models\Transaction::where('store_id', $store->id)->count();

        // Get balance
        $storeBalance = \App\Models\StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );
        $balance = $storeBalance->balance;

        return view('seller.dashboard', compact('store', 'totalProducts', 'totalOrders', 'balance'));

    } catch (\Exception $e) {
        Log::error('Error Seller Dashboard: '.$e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat dashboard.');
    }
}

    public function create()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            return view('seller.store.register');
        } catch (\Exception $e) {
            Log::error('Error Load Create Store: '.$e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka form.');
        }
    }

    public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'about'       => 'nullable|string|max:1000',
        'phone'       => 'nullable|string|max:50',
        'address'     => 'nullable|string|max:500',
        'city'        => 'required|string|max:100',
        'postal_code' => 'nullable|string|max:20',
        'logo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Logo default (atau bisa kosong '')
        $logoPath = 'store_logos/default.png';

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('store_logos', $fileName, 'public');
        }

        $store = new Store();
        $store->user_id      = $user->id;
        $store->name         = $request->name;
        $store->about        = $request->about;
        $store->phone        = $request->phone;
        $store->address      = $request->address;
        $store->city         = $request->city;
        $store->postal_code  = $request->postal_code;
        $store->logo         = $logoPath; // TIDAK NULL
        $store->address_id   = 'default';
        $store->is_verified  = false;
        $store->save();

        return redirect()->route('seller.dashboard')
            ->with('success', 'Toko berhasil dibuat. Menunggu verifikasi admin.');

    } catch (\Exception $e) {
        Log::error('Error Store Creation: '.$e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat membuat toko: '.$e->getMessage());
    }
}


    public function edit()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $store = $user->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                    ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            return view('seller.store.edit', compact('store'));

        } catch (\Exception $e) {
            Log::error('Error Load Edit Store: '.$e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuka form edit toko.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'about'       => 'nullable|string|max:1000',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string|max:500',
            'city'        => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $store = $user->store;

            if (!$store) {
                return redirect()->route('seller.store.create')
                    ->with('warning', 'Silakan buat toko terlebih dahulu.');
            }

            // Jika upload logo baru → hapus logo lama
            if ($request->hasFile('logo')) {
                // Hapus logo lama
                if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                    Storage::disk('public')->delete($store->logo);
                }

                // Upload baru
                $file = $request->file('logo');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $store->logo = $file->storeAs('store_logos', $fileName, 'public');
            }

            // Update field lain
            $store->name        = $request->name;
            $store->about       = $request->about;
            $store->phone       = $request->phone;
            $store->address     = $request->address;
            $store->city        = $request->city;
            $store->postal_code = $request->postal_code;
            $store->save();

            return redirect()->route('seller.store.edit')
                ->with('success', 'Profil toko berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error Update Store: '.$e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui toko: ' . $e->getMessage());
        }
    }

    public function destroy()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $store = $user->store;

            if (!$store) {
                return redirect()->route('seller.dashboard')
                    ->with('error', 'Toko tidak ditemukan.');
            }

            // Hapus logo jika ada
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }

            // Hapus store
            $store->delete();

            return redirect()->route('seller.dashboard')
                ->with('success', 'Toko berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error Delete Store: '.$e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus toko: ' . $e->getMessage());
        }
    }
}
