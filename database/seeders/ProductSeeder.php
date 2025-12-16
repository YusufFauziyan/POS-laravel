<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // Coffee
            ['name' => 'Espresso', 'category' => 'Coffee', 'price' => 25000, 'cost' => 8000, 'stock' => 100],
            ['name' => 'Cappuccino', 'category' => 'Coffee', 'price' => 30000, 'cost' => 10000, 'stock' => 80],
            ['name' => 'Latte', 'category' => 'Coffee', 'price' => 32000, 'cost' => 11000, 'stock' => 75],
            ['name' => 'Americano', 'category' => 'Coffee', 'price' => 28000, 'cost' => 9000, 'stock' => 90],
            ['name' => 'Mocha', 'category' => 'Coffee', 'price' => 35000, 'cost' => 12000, 'stock' => 60],
            ['name' => 'Caramel Macchiato', 'category' => 'Coffee', 'price' => 38000, 'cost' => 13000, 'stock' => 50],
            ['name' => 'Flat White', 'category' => 'Coffee', 'price' => 33000, 'cost' => 11000, 'stock' => 45],
            ['name' => 'Affogato', 'category' => 'Coffee', 'price' => 40000, 'cost' => 15000, 'stock' => 30],

            // Tea
            ['name' => 'Green Tea', 'category' => 'Tea', 'price' => 20000, 'cost' => 6000, 'stock' => 70],
            ['name' => 'Earl Grey', 'category' => 'Tea', 'price' => 22000, 'cost' => 7000, 'stock' => 65],
            ['name' => 'Chamomile Tea', 'category' => 'Tea', 'price' => 23000, 'cost' => 7500, 'stock' => 55],
            ['name' => 'Jasmine Tea', 'category' => 'Tea', 'price' => 21000, 'cost' => 6500, 'stock' => 60],
            ['name' => 'Matcha Latte', 'category' => 'Tea', 'price' => 35000, 'cost' => 12000, 'stock' => 40],
            ['name' => 'Thai Tea', 'category' => 'Tea', 'price' => 25000, 'cost' => 8000, 'stock' => 50],

            // Pastries
            ['name' => 'Croissant', 'category' => 'Pastries', 'price' => 18000, 'cost' => 5000, 'stock' => 35],
            ['name' => 'Pain au Chocolat', 'category' => 'Pastries', 'price' => 20000, 'cost' => 6000, 'stock' => 30],
            ['name' => 'Almond Croissant', 'category' => 'Pastries', 'price' => 22000, 'cost' => 7000, 'stock' => 25],
            ['name' => 'Cinnamon Roll', 'category' => 'Pastries', 'price' => 25000, 'cost' => 8000, 'stock' => 20],
            ['name' => 'Blueberry Muffin', 'category' => 'Pastries', 'price' => 18000, 'cost' => 5500, 'stock' => 40],
            ['name' => 'Chocolate Muffin', 'category' => 'Pastries', 'price' => 18000, 'cost' => 5500, 'stock' => 38],
            ['name' => 'Danish Pastry', 'category' => 'Pastries', 'price' => 20000, 'cost' => 6500, 'stock' => 28],

            // Snacks
            ['name' => 'Potato Chips', 'category' => 'Snacks', 'price' => 15000, 'cost' => 4000, 'stock' => 8],
            ['name' => 'Cookies', 'category' => 'Snacks', 'price' => 12000, 'cost' => 3500, 'stock' => 55],
            ['name' => 'Brownie', 'category' => 'Snacks', 'price' => 20000, 'cost' => 6000, 'stock' => 32],
            ['name' => 'Cheesecake Slice', 'category' => 'Snacks', 'price' => 28000, 'cost' => 10000, 'stock' => 18],
            ['name' => 'Tiramisu Slice', 'category' => 'Snacks', 'price' => 30000, 'cost' => 11000, 'stock' => 15],
            ['name' => 'Chocolate Cake Slice', 'category' => 'Snacks', 'price' => 25000, 'cost' => 9000, 'stock' => 22],

            // Sandwiches
            ['name' => 'Club Sandwich', 'category' => 'Sandwiches', 'price' => 35000, 'cost' => 12000, 'stock' => 25],
            ['name' => 'Tuna Sandwich', 'category' => 'Sandwiches', 'price' => 30000, 'cost' => 10000, 'stock' => 30],
            ['name' => 'Chicken Sandwich', 'category' => 'Sandwiches', 'price' => 32000, 'cost' => 11000, 'stock' => 28],
            ['name' => 'Egg Sandwich', 'category' => 'Sandwiches', 'price' => 25000, 'cost' => 8000, 'stock' => 35],
            ['name' => 'Veggie Sandwich', 'category' => 'Sandwiches', 'price' => 28000, 'cost' => 9000, 'stock' => 20],
            ['name' => 'BLT Sandwich', 'category' => 'Sandwiches', 'price' => 33000, 'cost' => 11500, 'stock' => 22],
        ];

        $imageCounter = 1;

        foreach ($products as $productData) {
            $category = $categories->firstWhere('name', $productData['category']);
            
            if ($category) {
                // Generate placeholder image using picsum.photos
                $imagePath = null;
                try {
                    $imageUrl = "https://picsum.photos/seed/product{$imageCounter}/400/400";
                    $imageContent = @file_get_contents($imageUrl);
                    
                    if ($imageContent !== false) {
                        $filename = 'product_' . time() . '_' . $imageCounter . '.jpg';
                        $path = storage_path('app/public/products/' . $filename);
                        file_put_contents($path, $imageContent);
                        $imagePath = 'products/' . $filename;
                    }
                } catch (\Exception $e) {
                    // If image download fails, continue without image
                }

                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'price' => $productData['price'],
                    'cost' => $productData['cost'],
                    'stock' => $productData['stock'],
                    'image' => $imagePath,
                    'is_active' => true,
                ]);

                $imageCounter++;
            }
        }

        $this->command->info('Created ' . count($products) . ' products with images.');
    }
}
