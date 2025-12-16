<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
              {{ company.name }}
            </h1>
            <p class="text-sm text-gray-600 mt-1">Discover our delicious offerings</p>
          </div>
          <Link href="/" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
            ← Back to Home
          </Link>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Search and Filters -->
      <div class="mb-8 space-y-4">
        <!-- Search Bar -->
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for your favorite items..."
            class="w-full px-5 py-3 pl-12 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all"
            @input="search"
          />
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <!-- Category Filter -->
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
          <button
            @click="filterByCategory(null)"
            :class="[
              'px-5 py-2 rounded-full font-medium text-sm whitespace-nowrap transition-all',
              !selectedCategory
                ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-200'
                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
            ]"
          >
            All Items
          </button>
          <button
            v-for="category in categories"
            :key="category.id"
            @click="filterByCategory(category.id)"
            :class="[
              'px-5 py-2 rounded-full font-medium text-sm whitespace-nowrap transition-all',
              selectedCategory === category.id
                ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-200'
                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
            ]"
          >
            {{ category.name }}
          </button>
        </div>
      </div>

      <!-- Products Grid -->
      <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-indigo-200"
        >
          <!-- Product Image -->
          <div class="relative h-48 bg-gradient-to-br from-indigo-100 to-purple-100 overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
              <svg class="w-20 h-20 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            
            <!-- Category Badge -->
            <div class="absolute top-3 left-3">
              <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-indigo-600">
                {{ product.category.name }}
              </span>
            </div>

            <!-- Low Stock Badge -->
            <div v-if="isLowStock(product.stock)" class="absolute top-3 right-3">
              <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-xs font-bold flex items-center gap-1 shadow-lg">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Only {{ product.stock }} left
              </span>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-5">
            <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-1">
              {{ product.name }}
            </h3>
            
            <p v-if="product.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
              {{ product.description }}
            </p>

            <div class="flex items-center justify-between mt-4">
              <div>
                <p class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                  {{ formatPrice(product.price) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                  {{ product.stock }} in stock
                </p>
              </div>
              
              <button class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium text-sm hover:shadow-lg hover:scale-105 transition-all">
                Order
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
          <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No items found</h3>
        <p class="text-gray-600">Try adjusting your search or filter to find what you're looking for.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
  company: Object,
  products: Array,
  categories: Array,
  filters: {
    type: Object,
    default: () => ({})
  },
});

console.log('Props received:', props);
console.log('Products:', props.products);
console.log('Products length:', props.products?.length);

onMounted(() => {
  console.log('Component mounted!');
  console.log('Products on mount:', props.products);
  console.log('Products length on mount:', props.products?.length);
});

watch(() => props.products, (newVal) => {
  console.log('Products changed:', newVal);
  console.log('New length:', newVal?.length);
}, { immediate: true, deep: true });

const searchQuery = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category_id || null);

const search = () => {
  router.get(`/menu/${props.company.slug}`, {
    search: searchQuery.value,
    category_id: selectedCategory.value,
  }, {
    preserveState: true,
    replace: true,
  });
};

const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
  router.get(`/menu/${props.company.slug}`, {
    search: searchQuery.value,
    category_id: categoryId,
  }, {
    preserveState: true,
    replace: true,
  });
};

const isLowStock = (stock) => {
  return stock < 10;
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(price);
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
