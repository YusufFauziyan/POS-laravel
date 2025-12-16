<template>
  <AppLayout title="Products">
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
      <div class="flex-1 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search products..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          @input="search"
        />
        <select
          v-model="categoryFilter"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          @change="search"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>
      <Link
        href="/products/create"
        class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition font-medium text-center"
      >
        + Add Product
      </Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div
        v-for="product in products.data"
        :key="product.id"
        class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition"
      >
        <div class="aspect-square bg-gray-100 relative">
          <img
            v-if="product.image"
            :src="`/storage/${product.image}`"
            :alt="product.name"
            class="w-full h-full object-cover"
          />
          <div v-else class="w-full h-full flex items-center justify-center">
            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <span
            v-if="!product.is_active"
            class="absolute top-2 right-2 px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded"
          >
            Inactive
          </span>
        </div>
        <div class="p-4">
          <div class="mb-2">
            <span class="text-xs text-gray-500">{{ product.category.name }}</span>
          </div>
          <h3 class="font-semibold text-gray-900 mb-2 truncate">{{ product.name }}</h3>
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="text-lg font-bold text-indigo-600">Rp {{ formatPrice(product.price) }}</p>
              <p v-if="product.cost" class="text-xs text-gray-500">Cost: Rp {{ formatPrice(product.cost) }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm font-medium" :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                Stock: {{ product.stock }}
              </p>
            </div>
          </div>
          <div class="flex gap-2">
            <Link
              :href="`/products/${product.id}/edit`"
              class="flex-1 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition text-center text-sm font-medium"
            >
              Edit
            </Link>
            <button
              @click="deleteProduct(product.id)"
              class="flex-1 px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium"
            >
              Delete
            </button>
          </div>
        </div>
      </div>

      <div v-if="products.data.length === 0" class="col-span-full text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="text-gray-500">No products found.</p>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="products.links && products.links.length > 3" class="mt-6 flex items-center justify-center">
      <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
        <Link
          v-for="(link, index) in products.links"
          :key="index"
          :href="link.url || '#'"
          :class="[
            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            index === 0 ? 'rounded-l-md' : '',
            index === products.links.length - 1 ? 'rounded-r-md' : '',
          ]"
          v-html="link.label"
        />
      </nav>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  products: Object,
  categories: Array,
  filters: {
    type: Object,
    default: () => ({})
  },
});

const searchQuery = ref(props.filters?.search || '');
const categoryFilter = ref(props.filters?.category || '');

const search = () => {
  router.get('/products', {
    search: searchQuery.value,
    category: categoryFilter.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

const deleteProduct = (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    router.delete(`/products/${id}`);
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};
</script>
