<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::factory()->create([
            'name' => 'Nicolle Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
        ]);

        // Create Default Cashier
        User::factory()->create([
            'name' => 'Nicolle Cashier',
            'username' => 'cashier1',
            'password' => Hash::make('user123'),
            'role' => 'Cashier',
        ]);

        // Create Initial Categories
        $categories = ['Beverages', 'Dairy', 'Snacks', 'Personal Care', 'Bakery', 'Canned Goods'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
