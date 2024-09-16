<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'type' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'type' => 'customer',
            'name' => 'Customer',
            'email' => 'customer@mail.com',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'type' => 'supplier',
            'name' => 'Supplier',
            'email' => 'supplier@mail.com',
            'password' => Hash::make('123'),
        ]);
        User::create([
            'type' => 'manager',
            'name' => 'Manager',
            'email' => 'manager@mail.com',
            'password' => Hash::make('123'),
        ]);
    }
}
