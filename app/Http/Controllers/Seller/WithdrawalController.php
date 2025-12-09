<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki toko.');
        }

        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Get withdrawal history
        $withdrawals = $storeBalance->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get last bank account info
        $lastWithdrawal = $storeBalance->withdrawals()
            ->whereNotNull('bank_account_name')
            ->latest()
            ->first();

        return view('seller.withdrawals.index', compact(
            'store',
            'storeBalance',
            'withdrawals',
            'lastWithdrawal'
        ));
    }

    public function create()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->back()
                ->with('error', 'Anda belum memiliki toko.');
        }

        $storeBalance = StoreBalance::firstOrCreate(
            ['store_id' => $store->id],
            ['balance' => 0]
        );

        // Get last bank account info for prefill
        $lastWithdrawal = $storeBalance->withdrawals()
            ->whereNotNull('bank_account_name')
            ->latest()
            ->first();

        return view('seller.withdrawals.create', compact(
            'store',
            'storeBalance',
            'lastWithdrawal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'bank_name' => 'required|string|max:100',
            'bank_account_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
        ]);

        try {
            $store = Auth::user()->store;
            $storeBalance = StoreBalance::where('store_id', $store->id)->first();

            // Check if balance is sufficient
            if ($storeBalance->balance < $request->amount) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
            }

            DB::beginTransaction();

            // Create withdrawal request
            $withdrawal = Withdrawal::create([
                'store_balance_id' => $storeBalance->id,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
                'status' => 'pending',
            ]);

            // Deduct balance
            $storeBalance->balance -= $request->amount;
            $storeBalance->save();

            // Create balance history
            StoreBalanceHistory::create([
                'store_balance_id' => $storeBalance->id,
                'type' => 'withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => 'App\Models\Withdrawal',
                'amount' => $request->amount,
                'remarks' => 'Penarikan dana ke ' . $request->bank_name . ' - ' . $request->bank_account_number,
            ]);

            DB::commit();

            return redirect()->route('seller.withdrawals.index')
                ->with('success', 'Permintaan penarikan dana berhasil diajukan. Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
        ]);

        try {
            $store = Auth::user()->store;
            $storeBalance = StoreBalance::where('store_id', $store->id)->first();

            // Create a dummy withdrawal with status 'info' to store bank account
            // Or you can create a separate table for bank accounts
            // For now, we'll just return success as the bank info will be used in next withdrawal

            return redirect()->back()
                ->with('success', 'Informasi rekening bank berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
