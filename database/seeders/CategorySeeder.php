<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee', 'description' => 'Hot and cold coffee beverages', 'is_active' => true],
            ['name' => 'Tea', 'description' => 'Various tea selections', 'is_active' => true],
            ['name' => 'Pastries', 'description' => 'Fresh baked goods', 'is_active' => true],
            ['name' => 'Snacks', 'description' => 'Light snacks and treats', 'is_active' => true],
            ['name' => 'Sandwiches', 'description' => 'Fresh sandwiches', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
