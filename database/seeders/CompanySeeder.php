<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Company A
        $companyA = Company::create([
            'name' => 'Company A',
            'email' => 'contact@company-a.test',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'is_active' => true,
        ]);

        // Create users for Company A
        User::create([
            'company_id' => $companyA->id,
            'name' => 'Owner A',
            'email' => 'owner-a@company-a.test',
            'password' => Hash::make('password'),
            'role' => UserRole::OWNER,
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $companyA->id,
            'name' => 'Admin A',
            'email' => 'admin-a@company-a.test',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $companyA->id,
            'name' => 'Cashier A',
            'email' => 'cashier-a@company-a.test',
            'password' => Hash::make('password'),
            'role' => UserRole::CASHIER,
            'is_active' => true,
        ]);

        // Create categories for Company A
        $categoryA1 = Category::create([
            'company_id' => $companyA->id,
            'name' => 'Beverages',
            'description' => 'Drinks and beverages',
            'is_active' => true,
        ]);

        $categoryA2 = Category::create([
            'company_id' => $companyA->id,
            'name' => 'Snacks',
            'description' => 'Snacks and light food',
            'is_active' => true,
        ]);

        // Create products for Company A
        Product::create([
            'company_id' => $companyA->id,
            'category_id' => $categoryA1->id,
            'name' => 'Coffee',
            'price' => 25000,
            'cost' => 15000,
            'stock' => 100,
            'is_active' => true,
        ]);

        Product::create([
            'company_id' => $companyA->id,
            'category_id' => $categoryA1->id,
            'name' => 'Tea',
            'price' => 15000,
            'cost' => 8000,
            'stock' => 150,
            'is_active' => true,
        ]);

        Product::create([
            'company_id' => $companyA->id,
            'category_id' => $categoryA2->id,
            'name' => 'Chips',
            'price' => 10000,
            'cost' => 5000,
            'stock' => 200,
            'is_active' => true,
        ]);

        // Create Company B
        $companyB = Company::create([
            'name' => 'Company B',
            'email' => 'contact@company-b.test',
            'phone' => '089876543210',
            'address' => 'Jl. Contoh No. 456, Bandung',
            'is_active' => true,
        ]);

        // Create users for Company B
        User::create([
            'company_id' => $companyB->id,
            'name' => 'Owner B',
            'email' => 'owner-b@company-b.test',
            'password' => Hash::make('password'),
            'role' => UserRole::OWNER,
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $companyB->id,
            'name' => 'Admin B',
            'email' => 'admin-b@company-b.test',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        User::create([
            'company_id' => $companyB->id,
            'name' => 'Cashier B',
            'email' => 'cashier-b@company-b.test',
            'password' => Hash::make('password'),
            'role' => UserRole::CASHIER,
            'is_active' => true,
        ]);

        // Create categories for Company B
        $categoryB1 = Category::create([
            'company_id' => $companyB->id,
            'name' => 'Electronics',
            'description' => 'Electronic devices',
            'is_active' => true,
        ]);

        $categoryB2 = Category::create([
            'company_id' => $companyB->id,
            'name' => 'Accessories',
            'description' => 'Phone and computer accessories',
            'is_active' => true,
        ]);

        // Create products for Company B
        Product::create([
            'company_id' => $companyB->id,
            'category_id' => $categoryB1->id,
            'name' => 'Mouse',
            'price' => 150000,
            'cost' => 100000,
            'stock' => 50,
            'is_active' => true,
        ]);

        Product::create([
            'company_id' => $companyB->id,
            'category_id' => $categoryB1->id,
            'name' => 'Keyboard',
            'price' => 300000,
            'cost' => 200000,
            'stock' => 30,
            'is_active' => true,
        ]);

        Product::create([
            'company_id' => $companyB->id,
            'category_id' => $categoryB2->id,
            'name' => 'Phone Case',
            'price' => 50000,
            'cost' => 25000,
            'stock' => 100,
            'is_active' => true,
        ]);
    }
}
