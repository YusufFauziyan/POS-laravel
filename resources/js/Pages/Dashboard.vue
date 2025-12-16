<template>
  <AppLayout title="Dashboard">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <!-- Today's Sales -->
      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium opacity-90">Today's Sales</h3>
          <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <p class="text-3xl font-bold mb-1">Rp {{ formatPrice(stats.today_sales.total) }}</p>
        <p class="text-sm opacity-90">{{ stats.today_sales.count }} orders</p>
      </div>

      <!-- This Month's Sales -->
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium opacity-90">This Month</h3>
          <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <p class="text-3xl font-bold mb-1">Rp {{ formatPrice(stats.month_sales.total) }}</p>
        <p class="text-sm opacity-90">{{ stats.month_sales.count}} orders</p>
      </div>

      <!-- Total Products -->
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium opacity-90">Total Products</h3>
          <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <p class="text-3xl font-bold mb-1">{{ stats.total_products }}</p>
        <p class="text-sm opacity-90">in inventory</p>
      </div>

      <!-- Low Stock Alert -->
      <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium opacity-90">Low Stock Alert</h3>
          <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <p class="text-3xl font-bold mb-1">{{ stats.low_stock_count }}</p>
        <p class="text-sm opacity-90">products need restock</p>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Daily Sales Chart -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Sales (Last 7 Days)</h3>
        <Line :data="salesChartData" :options="chartOptions" />
      </div>

      <!-- Payment Method Distribution -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h3>
        <Doughnut v-if="payment_stats && payment_stats.length > 0" :data="paymentChartData" :options="doughnutOptions" />
        <p v-else class="text-center text-gray-500 py-8">No payment data available</p>
      </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Orders -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
          <Link href="/orders" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
            View All →
          </Link>
        </div>
        <div class="space-y-3">
          <div
            v-for="order in recent_orders"
            :key="order.id"
            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
          >
            <div class="flex-1">
              <p class="font-semibold text-sm text-indigo-600">{{ order.order_number }}</p>
              <p class="text-xs text-gray-500">{{ order.items_count }} items • {{ formatDate(order.created_at) }}</p>
            </div>
            <p class="font-bold text-sm">Rp {{ formatPrice(order.grand_total) }}</p>
          </div>
          <div v-if="recent_orders.length === 0" class="text-center py-8 text-gray-500">
            No orders yet
          </div>
        </div>
      </div>

      <!-- Best Selling Products -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Best Selling Products</h3>
        <div class="space-y-3">
          <div
            v-for="(product, index) in best_selling"
            :key="index"
            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
          >
            <div class="flex items-center gap-3 flex-1">
              <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                {{ index + 1 }}
              </div>
              <div class="flex-1">
                <p class="font-semibold text-sm">{{ product.product_name }}</p>
                <p class="text-xs text-gray-500">{{ product.total_sold }} sold</p>
              </div>
            </div>
            <p class="font-bold text-sm text-green-600">Rp {{ formatPrice(product.revenue) }}</p>
          </div>
          <div v-if="best_selling.length === 0" class="text-center py-8 text-gray-500">
            No sales data yet
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Line, Doughnut } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';
import { computed } from 'vue';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
  stats: Object,
  recent_orders: Array,
  best_selling: Array,
  chart_data: Array,
  payment_stats: {
    type: Array,
    default: () => []
  },
});

const salesChartData = computed(() => ({
  labels: props.chart_data.map(d => new Date(d.date).toLocaleDateString('id-ID', { month: 'short', day: 'numeric' })),
  datasets: [{
    label: 'Sales',
    data: props.chart_data.map(d => d.total),
    borderColor: 'rgb(99, 102, 241)',
    backgroundColor: 'rgba(99, 102, 241, 0.1)',
    fill: true,
    tension: 0.4,
  }],
}));

const paymentChartData = computed(() => ({
  labels: (props.payment_stats || []).map(p => formatPaymentMethod(p.payment_method)),
  datasets: [{
    data: (props.payment_stats || []).map(p => p.total),
    backgroundColor: [
      'rgba(34, 197, 94, 0.8)',
      'rgba(59, 130, 246, 0.8)',
      'rgba(168, 85, 247, 0.8)',
    ],
  }],
}));

const chartOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    legend: {
      display: false,
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: (value) => 'Rp ' + formatPrice(value),
      },
    },
  },
};

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    legend: {
      position: 'bottom',
    },
    tooltip: {
      callbacks: {
        label: (context) => {
          return context.label + ': Rp ' + formatPrice(context.parsed);
        },
      },
    },
  },
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatPaymentMethod = (method) => {
  const methods = {
    cash: 'Cash',
    qris: 'QRIS',
    ewallet: 'E-Wallet',
  };
  return methods[method] || method;
};
</script>
