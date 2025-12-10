<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki toko.');
        }

        // Query awal
        $query = Transaction::where('store_id', $store->id)
            ->with(['buyer.user', 'transactionDetails.product']);

        // Filter berdasarkan status pembayaran (sekarang nama field: status)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter tracking (resinya)
        if ($request->has('tracking_status')) {
            if ($request->tracking_status == 'unshipped') {
                $query->whereNull('tracking_number');
            } elseif ($request->tracking_status == 'shipped') {
                $query->whereNotNull('tracking_number');
            }
        }

        // Pencarian kode order
        if ($request->has('search') && $request->search != '') {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistik
        $totalOrders = Transaction::where('store_id', $store->id)->count();

        $unpaidOrders = Transaction::where('store_id', $store->id)
            ->where('status', 'unpaid') // sekarang pakai status
            ->count();

        $unshippedOrders = Transaction::where('store_id', $store->id)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();

        return view('seller.orders.index', compact(
            'store',
            'orders',
            'totalOrders',
            'unpaidOrders',
            'unshippedOrders'
        ));
    }

    public function show($id)
    {
        $store = Auth::user()->store;

        $order = Transaction::where('store_id', $store->id)
            ->with(['buyer.user', 'transactionDetails.product', 'store'])
            ->findOrFail($id);

        return view('seller.orders.show', compact('store', 'order'));
    }

    public function updateTracking(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_type' => 'required|string|max:100', // field baru
        ]);

        try {
            $store = Auth::user()->store;
            $order = Transaction::where('store_id', $store->id)->findOrFail($id);

            // Cek apakah sudah dibayar
            if ($order->status != 'paid') {
                return redirect()->back()
                    ->with('error', 'Pesanan belum dibayar. Tidak dapat menambahkan nomor resi.');
            }

            DB::beginTransaction();

            $order->tracking_number = $request->tracking_number;
            $order->shipping_type = $request->shipping_type; // field baru
            $order->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Nomor resi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateShipping(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'shipping_type' => 'required|string|max:100', // field baru
        ]);

        try {
            $store = Auth::user()->store;
            $order = Transaction::where('store_id', $store->id)->findOrFail($id);

            $order->tracking_number = $request->tracking_number;
            $order->shipping_type = $request->shipping_type;
            $order->save();

            return redirect()->back()
                ->with('success', 'Informasi pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
