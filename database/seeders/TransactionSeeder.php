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

            if (!$store) {
                $this->command->error('Store not found! Run StoreBalanceSeeder first.');
                return;
            }

            if (!$buyer) {
                $this->command->error('Buyer not found! Run BuyerSeeder first.');
                return;
            }

            if ($products->count() == 0) {
                $this->command->error('Products not found! Run ProductSeeder first.');
                return;
            }

            $this->command->info('Creating transactions...');

            // Transaction 1 - Paid & Shipped
            $product1 = $products[0];
            $qty1 = 1;
            $subtotal1 = $product1->price * $qty1;
            $shippingCost1 = 25000;
            $tax1 = $subtotal1 * 0.01; // 1% tax
            $grandTotal1 = $subtotal1 + $shippingCost1 + $tax1;

            $transaction1 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Sudirman No. 123, RT 001/RW 002, Kelurahan Menteng',
                'address_id' => 'ADDR001',
                'city' => 'Jakarta Pusat',
                'postal_code' => '10310',
                'shipping' => 'JNE',
                'shipping_type' => 'REG',
                'shipping_cost' => $shippingCost1,
                'tracking_number' => 'JNE' . rand(100000000, 999999999),
                'tax' => $tax1,
                'grand_total' => $grandTotal1,
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction1->id,
                'product_id' => $product1->id,
                'qty' => $qty1,
                'subtotal' => $subtotal1,
            ]);

            $this->command->info('✓ Transaction 1 created: ' . $transaction1->code . ' (Paid & Shipped)');

            // Transaction 2 - Paid but not shipped
            $product2 = $products[1];
            $qty2 = 2;
            $subtotal2 = $product2->price * $qty2;
            $shippingCost2 = 15000;
            $tax2 = $subtotal2 * 0.01;
            $grandTotal2 = $subtotal2 + $shippingCost2 + $tax2;

            $transaction2 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Dago No. 456, RT 003/RW 004, Kelurahan Dago',
                'address_id' => 'ADDR002',
                'city' => 'Bandung',
                'postal_code' => '40135',
                'shipping' => 'J&T',
                'shipping_type' => 'YES',
                'shipping_cost' => $shippingCost2,
                'tracking_number' => null,
                'tax' => $tax2,
                'grand_total' => $grandTotal2,
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction2->id,
                'product_id' => $product2->id,
                'qty' => $qty2,
                'subtotal' => $subtotal2,
            ]);

            $this->command->info('✓ Transaction 2 created: ' . $transaction2->code . ' (Paid - Need Shipping)');

            // Transaction 3 - Unpaid
            $product3 = $products[2];
            $qty3 = 1;
            $subtotal3 = $product3->price * $qty3;
            $shippingCost3 = 18000;
            $tax3 = $subtotal3 * 0.01;
            $grandTotal3 = $subtotal3 + $shippingCost3 + $tax3;

            $transaction3 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Tunjungan No. 789, RT 005/RW 006, Kelurahan Genteng',
                'address_id' => 'ADDR003',
                'city' => 'Surabaya',
                'postal_code' => '60275',
                'shipping' => 'SiCepat',
                'shipping_type' => 'REGULER',
                'shipping_cost' => $shippingCost3,
                'tracking_number' => null,
                'tax' => $tax3,
                'grand_total' => $grandTotal3,
                'payment_status' => 'unpaid',
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction3->id,
                'product_id' => $product3->id,
                'qty' => $qty3,
                'subtotal' => $subtotal3,
            ]);

            $this->command->info('✓ Transaction 3 created: ' . $transaction3->code . ' (Unpaid)');

            // Transaction 4 - Multiple products, Paid & Shipped
            $product4 = $products[3];
            $product5 = $products[1];
            $qty4a = 1;
            $qty4b = 3;
            $subtotal4a = $product4->price * $qty4a;
            $subtotal4b = $product5->price * $qty4b;
            $subtotal4 = $subtotal4a + $subtotal4b;
            $shippingCost4 = 20000;
            $tax4 = $subtotal4 * 0.01;
            $grandTotal4 = $subtotal4 + $shippingCost4 + $tax4;

            $transaction4 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Malioboro No. 100, RT 007/RW 008, Kelurahan Sosromenduran',
                'address_id' => 'ADDR004',
                'city' => 'Yogyakarta',
                'postal_code' => '55271',
                'shipping' => 'Anteraja',
                'shipping_type' => 'REGULAR',
                'shipping_cost' => $shippingCost4,
                'tracking_number' => 'ANT' . rand(100000000, 999999999),
                'tax' => $tax4,
                'grand_total' => $grandTotal4,
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction4->id,
                'product_id' => $product4->id,
                'qty' => $qty4a,
                'subtotal' => $subtotal4a,
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction4->id,
                'product_id' => $product5->id,
                'qty' => $qty4b,
                'subtotal' => $subtotal4b,
            ]);

            $this->command->info('✓ Transaction 4 created: ' . $transaction4->code . ' (Multiple products, Paid & Shipped)');

            // Transaction 5 - Paid, waiting for shipping
            $product6 = $products[0];
            $qty5 = 2;
            $subtotal5 = $product6->price * $qty5;
            $shippingCost5 = 30000;
            $tax5 = $subtotal5 * 0.01;
            $grandTotal5 = $subtotal5 + $shippingCost5 + $tax5;

            $transaction5 = Transaction::create([
                'code' => 'ORD-' . strtoupper(Str::random(8)),
                'buyer_id' => $buyer->id,
                'store_id' => $store->id,
                'address' => 'Jl. Gatot Subroto No. 234, RT 009/RW 010, Kelurahan Kuningan',
                'address_id' => 'ADDR005',
                'city' => 'Jakarta Selatan',
                'postal_code' => '12950',
                'shipping' => 'Ninja Express',
                'shipping_type' => 'HEMAT',
                'shipping_cost' => $shippingCost5,
                'tracking_number' => null,
                'tax' => $tax5,
                'grand_total' => $grandTotal5,
                'payment_status' => 'paid',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction5->id,
                'product_id' => $product6->id,
                'qty' => $qty5,
                'subtotal' => $subtotal5,
            ]);

            $this->command->info('✓ Transaction 5 created: ' . $transaction5->code . ' (Paid - Need Shipping)');

            $this->command->info('');
            $this->command->info('========================================');
            $this->command->info('Transaction Summary:');
            $this->command->info('- Total: 5 transactions');
            $this->command->info('- Paid & Shipped: 2');
            $this->command->info('- Paid (Need Shipping): 3');
            $this->command->info('- Unpaid: 1');
            $this->command->info('========================================');
        });
    }
}
