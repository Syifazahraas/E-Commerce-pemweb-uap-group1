<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Buyer;
use Illuminate\Support\Facades\DB;

class BuyerSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Create buyer user
            $buyerUser = User::firstOrCreate(
                ['email' => 'buyer@example.com'],
                [
                    'name' => 'Buyer Demo',
                    'password' => bcrypt('password'),
                    'role' => 'member'
                ]
            );

            $this->command->info('✓ Buyer user created: ' . $buyerUser->email);

            // Create buyer profile
            $buyer = Buyer::firstOrCreate(
                ['user_id' => $buyerUser->id],
                [
                    'profile_picture' => null,
                    'phone_number' => '081234567890',
                ]
            );

            $this->command->info('✓ Buyer profile created for: ' . $buyerUser->name);
            $this->command->info('');
            $this->command->info('Buyer Login Credentials:');
            $this->command->info('Email: buyer@example.com');
            $this->command->info('Password: password');
        });
    }
}
