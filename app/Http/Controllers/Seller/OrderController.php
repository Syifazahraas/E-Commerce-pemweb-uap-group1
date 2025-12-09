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

        // Get orders with filter
        $query = Transaction::where('store_id', $store->id)
            ->with(['buyer.user', 'transactionDetails.product']);

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by tracking status
        if ($request->has('tracking_status')) {
            if ($request->tracking_status == 'unshipped') {
                $query->whereNull('tracking_number');
            } elseif ($request->tracking_status == 'shipped') {
                $query->whereNotNull('tracking_number');
            }
        }

        // Search by order code
        if ($request->has('search') && $request->search != '') {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $totalOrders = Transaction::where('store_id', $store->id)->count();
        $unpaidOrders = Transaction::where('store_id', $store->id)
            ->where('payment_status', 'unpaid')
            ->count();
        $unshippedOrders = Transaction::where('store_id', $store->id)
            ->where('payment_status', 'paid')
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
            'shipping' => 'required|string|max:100',
        ]);

        try {
            $store = Auth::user()->store;

            $order = Transaction::where('store_id', $store->id)->findOrFail($id);

            // Check if order is paid
            if ($order->payment_status != 'paid') {
                return redirect()->back()
                    ->with('error', 'Pesanan belum dibayar. Tidak dapat menambahkan nomor resi.');
            }

            DB::beginTransaction();

            $order->tracking_number = $request->tracking_number;
            $order->shipping = $request->shipping;
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
            'shipping' => 'required|string|max:100',
        ]);

        try {
            $store = Auth::user()->store;

            $order = Transaction::where('store_id', $store->id)->findOrFail($id);

            $order->tracking_number = $request->tracking_number;
            $order->shipping = $request->shipping;
            $order->save();

            return redirect()->back()
                ->with('success', 'Informasi pengiriman berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
