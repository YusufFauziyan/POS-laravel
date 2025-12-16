<template>
  <AppLayout title="Order Details">
    <div class="max-w-4xl">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <Link href="/orders" class="text-indigo-600 hover:text-indigo-800 mb-2 inline-block">
            ← Back to Orders
          </Link>
          <h1 class="text-2xl font-bold text-gray-900">Order {{ order.order_number }}</h1>
        </div>
        <span class="px-3 py-1 text-sm font-semibold rounded-full" :class="getStatusBadge(order.status)">
          {{ formatStatus(order.status) }}
        </span>
      </div>

      <!-- Order Info -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Order Number</p>
            <p class="font-semibold">{{ order.order_number }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Date & Time</p>
            <p class="font-semibold">{{ formatDate(order.created_at) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Cashier</p>
            <p class="font-semibold">{{ order.user.name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Payment Method</p>
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" :class="getPaymentBadge(order.payment_method)">
              {{ formatPaymentMethod(order.payment_method) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
        <table class="min-w-full">
          <thead class="border-b border-gray-200">
            <tr>
              <th class="text-left py-2 text-sm font-medium text-gray-500">Product</th>
              <th class="text-right py-2 text-sm font-medium text-gray-500">Price</th>
              <th class="text-center py-2 text-sm font-medium text-gray-500">Qty</th>
              <th class="text-right py-2 text-sm font-medium text-gray-500">Subtotal</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="item in order.items" :key="item.id">
              <td class="py-3">
                <p class="font-medium text-gray-900">{{ item.product_name }}</p>
              </td>
              <td class="py-3 text-right text-gray-600">
                Rp {{ formatPrice(item.price) }}
              </td>
              <td class="py-3 text-center text-gray-600">
                {{ item.quantity }}
              </td>
              <td class="py-3 text-right font-semibold text-gray-900">
                Rp {{ formatPrice(item.subtotal) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Order Summary -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
        <div class="space-y-2">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold">Rp {{ formatPrice(order.subtotal) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Tax (10%)</span>
            <span class="font-semibold">Rp {{ formatPrice(order.tax) }}</span>
          </div>
          <div v-if="order.discount > 0" class="flex justify-between text-sm text-green-600">
            <span>Discount</span>
            <span class="font-semibold">- Rp {{ formatPrice(order.discount) }}</span>
          </div>
          <div class="border-t border-gray-200 pt-2 flex justify-between">
            <span class="font-bold text-lg">Grand Total</span>
            <span class="font-bold text-2xl text-indigo-600">Rp {{ formatPrice(order.grand_total) }}</span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  order: Object,
});

const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const formatPaymentMethod = (method) => {
  const methods = {
    cash: 'Cash',
    qris: 'QRIS',
    ewallet: 'E-Wallet',
  };
  return methods[method] || method;
};

const formatStatus = (status) => {
  const statuses = {
    pending: 'Pending',
    completed: 'Completed',
    cancelled: 'Cancelled',
  };
  return statuses[status] || status;
};

const getPaymentBadge = (method) => {
  const badges = {
    cash: 'bg-green-100 text-green-800',
    qris: 'bg-blue-100 text-blue-800',
    ewallet: 'bg-purple-100 text-purple-800',
  };
  return badges[method] || 'bg-gray-100 text-gray-800';
};

const getStatusBadge = (status) => {
  const badges = {
    pending: 'bg-yellow-100 text-yellow-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return badges[status] || 'bg-gray-100 text-gray-800';
};
</script>
