<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockLog;
use App\Models\User;
use App\OrderStatus;
use App\PaymentMethod;
use App\StockLogType;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        $orderCounter = 1;

        // Create orders for the last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $ordersPerDay = rand(3, 8); // 3-8 orders per day
            
            for ($j = 0; $j < $ordersPerDay; $j++) {
                $user = $users->random();
                $createdAt = now()->subDays($i)->addHours(rand(8, 20))->addMinutes(rand(0, 59));
                
                // Random payment method
                $paymentMethods = [PaymentMethod::CASH, PaymentMethod::QRIS, PaymentMethod::EWALLET];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Generate order number manually
                $orderNumber = sprintf('ORD-%s-%04d', $createdAt->format('Ymd'), $orderCounter++);
                
                // Create order
                $order = new Order([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'subtotal' => 0,
                    'tax' => 0,
                    'discount' => 0,
                    'grand_total' => 0,
                    'payment_method' => $paymentMethod,
                    'status' => OrderStatus::COMPLETED,
                ]);
                
                // Set timestamps manually
                $order->created_at = $createdAt;
                $order->updated_at = $createdAt;
                $order->save();

                // Add 1-5 random items to order
                $itemCount = rand(1, 5);
                $subtotal = 0;
                
                for ($k = 0; $k < $itemCount; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $itemSubtotal = $product->price * $quantity;
                    $subtotal += $itemSubtotal;
                    
                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $itemSubtotal,
                    ]);
                    
                    // Reduce stock
                    $product->decrement('stock', $quantity);
                    
                    // Create stock log
                    StockLog::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'type' => StockLogType::OUT,
                        'quantity' => -$quantity,
                        'note' => "Order #{$order->order_number}",
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
                
                // Calculate totals
                $tax = $subtotal * 0.1;
                $discount = rand(0, 1) ? rand(0, 10000) : 0; // 50% chance of discount
                $grandTotal = $subtotal + $tax - $discount;
                
                // Update order totals
                $order->update([
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                ]);
            }
        }

        $this->command->info('Created orders for the last 30 days with realistic data.');
    }
}
