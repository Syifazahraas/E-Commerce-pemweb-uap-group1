<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function index()
    {
        // ambil store milik user
        $store = Auth::user()->store;

        // kalau user belum punya store
        if (!$store) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki toko. Silakan buat toko terlebih dahulu.');
        }

        // ambil atau buat balance
        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // ambil history saldo
        $histories = $storeBalance->storeBalanceHistories()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // hitung income
        $totalIncome = $storeBalance->storeBalanceHistories()
            ->where('type', 'income')
            ->sum('amount');

        // hitung withdraw
        $totalWithdraw = $storeBalance->storeBalanceHistories()
            ->where('type', 'withdraw')
            ->sum('amount');

        return view('seller.balance.index', compact(
            'store',
            'storeBalance',
            'histories',
            'totalIncome',
            'totalWithdraw'
        ));
    }
}
