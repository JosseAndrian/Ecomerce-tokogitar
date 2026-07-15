<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@musikstore.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // Customer
        User::create([
            'name'     => 'Customer Demo',
            'email'    => 'customer@musikstore.com',
            'password' => Hash::make('customer123'),
            'phone'    => '081234567890',
            'address'  => 'Jl. Margonda Raya No. 100, Depok',
            'role'     => 'customer',
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
