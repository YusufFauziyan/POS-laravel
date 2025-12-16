<template>
  <AppLayout title="Orders">
    <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
      <h1 class="text-2xl font-bold text-gray-900">Order History</h1>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-xl shadow-sm p-4 border border-gray-100">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search order number..."
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          @input="search"
        />
        <select
          v-model="statusFilter"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          @change="search"
        >
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <select
          v-model="paymentFilter"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          @change="search"
        >
          <option value="">All Payment Methods</option>
          <option value="cash">Cash</option>
          <option value="qris">QRIS</option>
          <option value="ewallet">E-Wallet</option>
        </select>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="font-semibold text-indigo-600">{{ order.order_number }}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ order.user.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ order.items_count }} items
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
              Rp {{ formatPrice(order.grand_total) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getPaymentBadge(order.payment_method)">
                {{ formatPaymentMethod(order.payment_method) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getStatusBadge(order.status)">
                {{ formatStatus(order.status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              <Link
                :href="`/orders/${order.id}`"
                class="text-indigo-600 hover:text-indigo-900 font-medium"
              >
                View
              </Link>
            </td>
          </tr>

          <tr v-if="orders.data.length === 0">
            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
              No orders found.
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="orders.links && orders.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <nav class="flex justify-center">
          <div class="flex gap-1">
            <Link
              v-for="(link, index) in orders.links"
              :key="index"
              :href="link.url || '#'"
              :class="[
                'px-4 py-2 text-sm font-medium rounded-lg',
                link.active
                  ? 'bg-indigo-600 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
              ]"
              v-html="link.label"
            />
          </div>
        </nav>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  orders: Object,
  filters: {
    type: Object,
    default: () => ({})
  },
});

const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const paymentFilter = ref(props.filters?.payment_method || '');

const search = () => {
  router.get('/orders', {
    search: searchQuery.value,
    status: statusFilter.value,
    payment_method: paymentFilter.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'short',
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
