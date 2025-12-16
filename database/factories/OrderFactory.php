<?php

namespace Database\Factories;

use App\Models\User;
use App\OrderStatus;
use App\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10000, 500000);
        $tax = $subtotal * 0.1;
        $discount = 0;
        
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . fake()->unique()->numerify('########'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'grand_total' => $subtotal + $tax - $discount,
            'payment_method' => fake()->randomElement([PaymentMethod::CASH, PaymentMethod::QRIS, PaymentMethod::EWALLET]),
            'status' => OrderStatus::COMPLETED,
        ];
    }
}
