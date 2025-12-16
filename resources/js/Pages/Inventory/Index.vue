<template>
  <AppLayout title="Inventory Management">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Inventory Management</h1>
      <p class="text-gray-600">Manage product stock levels and view stock history</p>
    </div>

    <!-- Search -->
    <div class="mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search products..."
        class="w-full md:w-96 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
        @input="search"
      />
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <p class="font-semibold text-gray-900">{{ product.name }}</p>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">
              {{ product.category.name }}
            </td>
            <td class="px-6 py-4">
              <span class="text-lg font-bold" :class="getStockColor(product.stock)">
                {{ product.stock }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getStockBadge(product.stock)">
                {{ getStockStatus(product.stock) }}
              </span>
            </td>
            <td class="px-6 py-4">
              <button
                @click="openAdjustModal(product)"
                class="text-indigo-600 hover:text-indigo-900 font-medium text-sm"
              >
                Adjust Stock
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="products.links && products.links.length > 3" class="px-6 py-4 border-t border-gray-200">
        <nav class="flex justify-center">
          <div class="flex gap-1">
            <Link
              v-for="(link, index) in products.links"
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

    <!-- Stock History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Stock Movements</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="border-b border-gray-200">
            <tr>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Date</th>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Product</th>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Type</th>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Quantity</th>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">User</th>
              <th class="text-left py-2 text-xs font-medium text-gray-500 uppercase">Note</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="log in stock_logs" :key="log.id">
              <td class="py-3 text-sm text-gray-600">{{ formatDate(log.created_at) }}</td>
              <td class="py-3 text-sm font-medium">{{ log.product.name }}</td>
              <td class="py-3">
                <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getTypeBadge(log.type)">
                  {{ formatType(log.type) }}
                </span>
              </td>
              <td class="py-3 font-semibold" :class="log.quantity > 0 ? 'text-green-600' : 'text-red-600'">
                {{ log.quantity > 0 ? '+' : '' }}{{ log.quantity }}
              </td>
              <td class="py-3 text-sm text-gray-600">{{ log.user.name }}</td>
              <td class="py-3 text-sm text-gray-600">{{ log.note }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div v-if="showAdjustModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-xl font-bold text-gray-900">Adjust Stock</h2>
          <p class="text-sm text-gray-600 mt-1">{{ selectedProduct?.name }}</p>
          <p class="text-sm text-gray-500">Current stock: <span class="font-bold">{{ selectedProduct?.stock }}</span></p>
        </div>

        <form @submit.prevent="submitAdjustment" class="p-6">
          <div class="space-y-4">
            <!-- Adjustment Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type</label>
              <div class="grid grid-cols-3 gap-2">
                <button
                  type="button"
                  v-for="type in adjustmentTypes"
                  :key="type.value"
                  @click="adjustForm.type = type.value"
                  :class="[
                    'px-4 py-2 rounded-lg border-2 font-medium text-sm transition',
                    adjustForm.type === type.value
                      ? 'border-indigo-600 bg-indigo-50 text-indigo-700'
                      : 'border-gray-200 hover:border-gray-300'
                  ]"
                >
                  {{ type.label }}
                </button>
              </div>
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ adjustForm.type === 'adjustment' ? 'New Stock Level' : 'Quantity' }}
              </label>
              <input
                v-model.number="adjustForm.quantity"
                type="number"
                min="1"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
              />
            </div>

            <!-- Note -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Note/Reason</label>
              <textarea
                v-model="adjustForm.note"
                required
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g., Received new shipment, Damaged goods, etc."
              ></textarea>
            </div>
          </div>

          <div class="mt-6 flex gap-3">
            <button
              type="button"
              @click="closeAdjustModal"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="adjustForm.processing"
              class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ adjustForm.processing ? 'Processing...' : 'Confirm' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  products: Object,
  stock_logs: Array,
  filters: {
    type: Object,
    default: () => ({})
  },
});

const searchQuery = ref(props.filters?.search || '');
const showAdjustModal = ref(false);
const selectedProduct = ref(null);

const adjustmentTypes = [
  { value: 'in', label: 'Stock In' },
  { value: 'out', label: 'Stock Out' },
  { value: 'adjustment', label: 'Set Stock' },
];

const adjustForm = useForm({
  product_id: null,
  type: 'in',
  quantity: 1,
  note: '',
});

const search = () => {
  router.get('/inventory', {
    search: searchQuery.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

const openAdjustModal = (product) => {
  selectedProduct.value = product;
  adjustForm.product_id = product.id;
  adjustForm.type = 'in';
  adjustForm.quantity = 1;
  adjustForm.note = '';
  showAdjustModal.value = true;
};

const closeAdjustModal = () => {
  showAdjustModal.value = false;
  selectedProduct.value = null;
};

const submitAdjustment = () => {
  adjustForm.post('/inventory/adjust', {
    onSuccess: () => {
      closeAdjustModal();
    },
  });
};

const getStockColor = (stock) => {
  if (stock === 0) return 'text-red-600';
  if (stock < 10) return 'text-orange-600';
  return 'text-green-600';
};

const getStockBadge = (stock) => {
  if (stock === 0) return 'bg-red-100 text-red-800';
  if (stock < 10) return 'bg-orange-100 text-orange-800';
  return 'bg-green-100 text-green-800';
};

const getStockStatus = (stock) => {
  if (stock === 0) return 'Out of Stock';
  if (stock < 10) return 'Low Stock';
  return 'In Stock';
};

const getTypeBadge = (type) => {
  const badges = {
    in: 'bg-green-100 text-green-800',
    out: 'bg-red-100 text-red-800',
    adjustment: 'bg-blue-100 text-blue-800',
  };
  return badges[type] || 'bg-gray-100 text-gray-800';
};

const formatType = (type) => {
  const types = {
    in: 'Stock In',
    out: 'Stock Out',
    adjustment: 'Adjustment',
  };
  return types[type] || type;
};

const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID', {
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>
