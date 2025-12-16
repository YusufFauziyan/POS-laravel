<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\UserRole;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view orders
        // (filtering by role happens in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Admin and Owner can view all orders
        if ($user->role === UserRole::ADMIN || $user->role === UserRole::OWNER) {
            return true;
        }

        // Cashier can only view their own orders
        if ($user->role === UserRole::CASHIER) {
            return $order->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin and Cashier can create orders
        return $user->role === UserRole::ADMIN || $user->role === UserRole::CASHIER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Only Admin can update orders
        return $user->role === UserRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only Admin can delete orders
        return $user->role === UserRole::ADMIN;
    }
}
