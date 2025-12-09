<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerAccess
{
    public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    // Only member can access seller panel
    if ($user->role !== 'member') {
        return redirect('/')
            ->with('error', 'Hanya member yang bisa masuk ke dashboard seller.');
    }

    $store = $user->store;

    // If no store → redirect to create
    if (!$store) {
        return redirect()->route('seller.store.create')
            ->with('error', 'Anda harus membuat toko terlebih dahulu.');
    }

    // If store not verified AND the user didn't open the CREATE form
    if ($store->status !== 'verified') {

        // 🚫 Jangan redirect ke edit, itu bikin loop
        // ✔ Redirect ke dashboard saja
        if (!$request->routeIs('seller.dashboard')) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Toko Anda belum diverifikasi.');
        }
    }

    return $next($request);
}

}
