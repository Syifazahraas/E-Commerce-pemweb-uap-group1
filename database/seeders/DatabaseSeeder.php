<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
{
    User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]
    );

    $this->call([
            StoreBalanceSeeder::class,    // 1. Buat Store, User Seller, Balance
            BuyerSeeder::class,            // 2. Buat Buyer User & Profile
            ProductSeeder::class,          // 3. Buat Categories & Products
            TransactionSeeder::class,      // 4. Buat Transactions
        ]);
}

}
