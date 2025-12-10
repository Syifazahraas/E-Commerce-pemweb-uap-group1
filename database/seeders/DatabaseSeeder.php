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
        // Create default admin user (from HEAD)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'admin',
                'password' => bcrypt('password'),
                'role'     => 'admin'
            ]
        );

        // Call all required seeders (merged)
        $this->call([
            AdminUserSeeder::class,       // from origin/user-syifa
            SellerSeeder::class,          // from origin/user-syifa
            // StoreBalanceSeeder::class,    // from HEAD
            // BuyerSeeder::class,           // both branches
            // ProductSeeder::class,         // both branches
            // TransactionSeeder::class,     // from HEAD
        ]);
    }
}
