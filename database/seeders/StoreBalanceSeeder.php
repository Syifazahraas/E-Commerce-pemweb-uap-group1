<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\StoreBalance;
use App\Models\StoreBalanceHistory;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreBalanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // ============================
            // 1. Buat user seller
            // ============================
            $seller = User::firstOrCreate(
                ['email' => 'seller@example.com'],
                [
                    'name' => 'Seller Dummy',
                    'password' => bcrypt('password'),
                    'role' => 'member' // karena enum hanya admin & member
                ]
            );

            $this->command->info('✓ User seller created/found: ' . $seller->email);

            // ============================
            // 2. Buat store milik seller
            // ============================
            $store = Store::firstOrCreate(
                ['user_id' => $seller->id],
                [
                    'name' => 'Toko Dummy',
                    'logo' => 'store_logos/default.png',
                    'about' => 'Toko dummy untuk testing balance & withdrawal',
                    'phone' => '08123456789',
                    'address_id' => 'ADDR001',
                    'city' => 'Surabaya',
                    'address' => 'Jl. Dummy No. 123',
                    'postal_code' => '60123',
                    'is_verified' => true,
                ]
            );

            $this->command->info('✓ Store created/found: ' . $store->name);

            // ============================
            // 3. Buat store balance
            // ============================
            $storeBalance = StoreBalance::firstOrCreate(
                ['store_id' => $store->id],
                ['balance' => 0]
            );

            $this->command->info('✓ Store balance initialized');

            // ============================
            // 4. Buat beberapa income dummy
            // ============================
            $incomes = [
                [
                    'amount' => 250000,
                    'remarks' => 'Pembayaran dari pesanan #ORD001 - Laptop Gaming',
                    'days_ago' => 5
                ],
                [
                    'amount' => 150000,
                    'remarks' => 'Pembayaran dari pesanan #ORD002 - Mouse Wireless',
                    'days_ago' => 4
                ],
                [
                    'amount' => 320000,
                    'remarks' => 'Pembayaran dari pesanan #ORD003 - Keyboard Mechanical',
                    'days_ago' => 3
                ],
                [
                    'amount' => 180000,
                    'remarks' => 'Pembayaran dari pesanan #ORD004 - Webcam HD',
                    'days_ago' => 2
                ],
            ];

            foreach ($incomes as $income) {
                StoreBalanceHistory::create([
                    'store_balance_id' => $storeBalance->id,
                    'type' => 'income',
                    'reference_id' => (string) Str::uuid(),
                    'reference_type' => 'App\\Models\\Order',
                    'amount' => $income['amount'],
                    'remarks' => $income['remarks'],
                    'created_at' => Carbon::now()->subDays($income['days_ago']),
                    'updated_at' => Carbon::now()->subDays($income['days_ago']),
                ]);
            }

            $this->command->info('✓ Created ' . count($incomes) . ' income records');

            // ============================
            // 5. Buat beberapa withdrawal dummy
            // ============================
            $withdrawals = [
                [
                    'amount' => 200000,
                    'bank_name' => 'BCA',
                    'bank_account_name' => 'Seller Dummy',
                    'bank_account_number' => '1234567890',
                    'status' => 'approved',
                    'days_ago' => 3
                ],
                [
                    'amount' => 150000,
                    'bank_name' => 'Mandiri',
                    'bank_account_name' => 'Seller Dummy',
                    'bank_account_number' => '9876543210',
                    'status' => 'approved',
                    'days_ago' => 1
                ],
                [
                    'amount' => 100000,
                    'bank_name' => 'BCA',
                    'bank_account_name' => 'Seller Dummy',
                    'bank_account_number' => '1234567890',
                    'status' => 'pending',
                    'days_ago' => 0
                ],
            ];

            foreach ($withdrawals as $withdrawal) {
                // Create withdrawal record
                $withdrawalRecord = Withdrawal::create([
                    'store_balance_id' => $storeBalance->id,
                    'amount' => $withdrawal['amount'],
                    'bank_name' => $withdrawal['bank_name'],
                    'bank_account_name' => $withdrawal['bank_account_name'],
                    'bank_account_number' => $withdrawal['bank_account_number'],
                    'status' => $withdrawal['status'],
                    'created_at' => Carbon::now()->subDays($withdrawal['days_ago']),
                    'updated_at' => Carbon::now()->subDays($withdrawal['days_ago']),
                ]);

                // Create balance history for this withdrawal
                StoreBalanceHistory::create([
                    'store_balance_id' => $storeBalance->id,
                    'type' => 'withdraw',
                    'reference_id' => $withdrawalRecord->id,
                    'reference_type' => 'App\\Models\\Withdrawal',
                    'amount' => $withdrawal['amount'],
                    'remarks' => 'Penarikan dana ke ' . $withdrawal['bank_name'] . ' - ' . $withdrawal['bank_account_number'],
                    'created_at' => Carbon::now()->subDays($withdrawal['days_ago']),
                    'updated_at' => Carbon::now()->subDays($withdrawal['days_ago']),
                ]);
            }

            $this->command->info('✓ Created ' . count($withdrawals) . ' withdrawal records');

            // ============================
            // 6. Hitung dan update balance
            // ============================
            $totalIncome = StoreBalanceHistory::where('store_balance_id', $storeBalance->id)
                ->where('type', 'income')
                ->sum('amount');

            $totalWithdraw = StoreBalanceHistory::where('store_balance_id', $storeBalance->id)
                ->where('type', 'withdraw')
                ->sum('amount');

            $storeBalance->balance = $totalIncome - $totalWithdraw;
            $storeBalance->save();

            $this->command->info('✓ Balance calculated:');
            $this->command->info('  - Total Income: Rp ' . number_format($totalIncome, 0, ',', '.'));
            $this->command->info('  - Total Withdraw: Rp ' . number_format($totalWithdraw, 0, ',', '.'));
            $this->command->info('  - Current Balance: Rp ' . number_format($storeBalance->balance, 0, ',', '.'));

            // ============================
            // 7. Summary
            // ============================
            $this->command->info('');
            $this->command->info('========================================');
            $this->command->info('Store Balance Seeder completed!');
            $this->command->info('========================================');
            $this->command->info('Login credentials:');
            $this->command->info('Email: seller@example.com');
            $this->command->info('Password: password');
            $this->command->info('========================================');
        });
    }
}
