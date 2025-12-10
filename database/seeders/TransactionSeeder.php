<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Store;
use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $store = Store::first();
            $buyer = Buyer::first();
            $products = Product::where('store_id', $store->id)->get();

            if (!$store || !$buyer || $products->count() === 0) {
                $this->command->error('Store / Buyer / Products belum tersedia. Jalankan seeder lain.');
                return;
            }

            $this->command->info("Seeding Transactions...");

            // Helper ambil size produk
            $getSize = fn($product) =>
                (is_array($product->sizes) && count($product->sizes) > 0)
                    ? $product->sizes[0]
                    : "-";


            /* --------------------------------------------------------
             * TRANSAKSI 1 - SUDAH DIKIRIM
             * -------------------------------------------------------- */
            $transaction1 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id'  => $store->id,
                'address' => 'Jl. Sudirman No. 123',
                'city' => 'Jakarta Pusat',
                'postal_code' => '10310',
                'shipping_type' => 'REG',
                'shipping_cost' => 25000,
                'tracking_number' => 'JNE' . rand(100000000, 999999999),
                'grand_total' => $products[0]->price + 25000,
                'payment_method' => 'manual',
                'status' => 'shipped',
                'created_at' => Carbon::now()->subDays(5),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction1->id,
                'product_id' => $products[0]->id,
                'size' => $getSize($products[0]),
                'qty' => 1,
                'subtotal' => $products[0]->price,
            ]);

            $this->command->info("✓ Transaction 1 created");


            /* --------------------------------------------------------
             * TRANSAKSI 2 - SIAP DIINPUT RESI (paid)
             * -------------------------------------------------------- */
            $transaction2 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Dago No. 456',
                'city' => 'Bandung',
                'postal_code' => '40135',
                'shipping_type' => 'YES',
                'shipping_cost' => 15000,
                'tracking_number' => null, // belum ada resi
                'grand_total' => $products[1]->price * 2 + 15000,
                'payment_method' => 'manual',
                'status' => 'paid',   // ✔ bisa input resi
                'created_at' => Carbon::now()->subDays(2),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction2->id,
                'product_id' => $products[1]->id,
                'size' => $getSize($products[1]),
                'qty' => 2,
                'subtotal' => $products[1]->price * 2,
            ]);

            $this->command->info("✓ Transaction 2 (ready input resi) created");


            /* --------------------------------------------------------
             * TRANSAKSI 3 - BELUM DIBAYAR
             * -------------------------------------------------------- */
            $transaction3 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Tunjungan No. 789',
                'city' => 'Surabaya',
                'postal_code' => '60275',
                'shipping_type' => 'REG',
                'shipping_cost' => 18000,
                'tracking_number' => null,
                'grand_total' => $products[2]->price + 18000,
                'payment_method' => 'manual',
                'status' => 'waiting_payment',
                'created_at' => Carbon::now()->subDay(),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction3->id,
                'product_id' => $products[2]->id,
                'size' => $getSize($products[2]),
                'qty' => 1,
                'subtotal' => $products[2]->price,
            ]);

            $this->command->info("✓ Transaction 3 created");


            /* --------------------------------------------------------
             * TRANSAKSI 4 - SUDAH DIKIRIM MULTI PRODUK
             * -------------------------------------------------------- */
            $transaction4 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Malioboro No. 100',
                'city' => 'Yogyakarta',
                'postal_code' => '55271',
                'shipping_type' => 'REG',
                'shipping_cost' => 20000,
                'tracking_number' => 'ANT' . rand(100000000, 999999999),
                'grand_total' => ($products[3]->price * 1) + ($products[1]->price * 3) + 20000,
                'payment_method' => 'manual',
                'status' => 'shipped',
                'created_at' => Carbon::now()->subDays(3),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction4->id,
                'product_id' => $products[3]->id,
                'size' => $getSize($products[3]),
                'qty' => 1,
                'subtotal' => $products[3]->price,
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction4->id,
                'product_id' => $products[1]->id,
                'size' => $getSize($products[1]),
                'qty' => 3,
                'subtotal' => $products[1]->price * 3,
            ]);

            $this->command->info("✓ Transaction 4 created");


            /* --------------------------------------------------------
             * TRANSAKSI 5 - SIAP INPUT RESi (paid)
             * -------------------------------------------------------- */
            $transaction5 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Gatot Subroto No. 234',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12950',
                'shipping_type' => 'HEMAT',
                'shipping_cost' => 30000,
                'tracking_number' => null,
                'grand_total' => $products[0]->price * 2 + 30000,
                'payment_method' => 'manual',
                'status' => 'paid', // ✔ siap input resi
                'created_at' => Carbon::now(),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction5->id,
                'product_id' => $products[0]->id,
                'size' => $getSize($products[0]),
                'qty' => 2,
                'subtotal' => $products[0]->price * 2,
            ]);

            $this->command->info("✓ Transaction 5 (ready input resi) created");
        });
    }
}
