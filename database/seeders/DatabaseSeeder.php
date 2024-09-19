<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderRequest;
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

        // category creation
        Category::create([
            'name' => 'Monocrystalline'
        ]);
        Category::create([
            'name' => 'Polycrystalline'
        ]);

        // product creation
        Product::create([
            'supplier_id' => 3,
            'category_id' => 2,
            'name' => '440Wp / NUJC440',
            'description' => '108 half-cell N-type TOPCon solar panel designed for residential and commercial rooftop photovoltaic systems, with a black frame and white backsheet for uncompromising long-term reliability and performance.',
            'price' => 30000,
            'stock' => 15,
        ]);

        Product::create([
            'supplier_id' => 3,
            'category_id' => 1,
            'name' => '580Wp / NBJD580',
            'description' => '144 TOPCon half-cell bifacial double glass solar panel designed for large free-field photovoltaic systems, optimized for long-term reliability and performance.',
            'price' => 60000,
            'stock' => 8,
        ]);

        Product::create([
            'supplier_id' => 3,
            'category_id' => 1,
            'name' => '550Wp / NUJD550',
            'description' => '144 half-cell solar panel designed for large free-field and commercial rooftop photovoltaic systems, optimized for long-term reliability and performance.',
            'price' => 45000,
            'stock' => 12,
        ]);

        // order request creation
        OrderRequest::create([
            'customer_id' => 2,
            'product_id' => 1,
            'quantity' => 3,
            'status' => 'pending',
            'total_amount' => 90000,
            'shipping_address' => '99, Jalan TTDI 6/2, TTDI, 52000, Kuala Lumpur',
        ]);
    }
}
