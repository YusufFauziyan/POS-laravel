<template>
  <AppLayout title="Point of Sale">
    <div class="h-[calc(100vh-12rem)] lg:h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-4">
      <!-- Products Section -->
      <div class="flex-1 flex flex-col min-h-0">
        <!-- Search & Filter -->
        <div class="flex-shrink-0 space-y-3 mb-4">
          <!-- Search -->
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search products..."
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
          
          <!-- Categories -->
          <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <button
              @click="selectedCategory = null"
              :class="[
                'px-5 py-3 rounded-lg font-semibold whitespace-nowrap transition flex-shrink-0 text-sm',
                selectedCategory === null
                  ? 'bg-indigo-600 text-white shadow-md'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
              ]"
            >
              All
            </button>
            <button
              v-for="category in categories"
              :key="category.id"
              @click="selectedCategory = category.id"
              :class="[
                'px-5 py-3 rounded-lg font-semibold whitespace-nowrap transition flex-shrink-0 text-sm',
                selectedCategory === category.id
                  ? 'bg-indigo-600 text-white shadow-md'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto">
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <button
              v-for="product in filteredProducts"
              :key="product.id"
              @click="addToCart(product)"
              class="bg-white rounded-lg border border-gray-200 hover:border-indigo-400 hover:shadow-md transition p-3 text-left group"
            >
              <div class="aspect-square bg-gray-100 rounded-lg mb-2 overflow-hidden">
                <img
                  v-if="product.image"
                  :src="`/storage/${product.image}`"
                  :alt="product.name"
                  class="w-full h-full object-cover group-hover:scale-105 transition"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
              </div>
              <h3 class="font-semibold text-sm mb-1 truncate text-gray-900">{{ product.name }}</h3>
              <p class="text-indigo-600 font-bold text-sm">Rp {{ formatPrice(product.price) }}</p>
              <p class="text-xs text-gray-500 mt-1">Stock: {{ product.stock }}</p>
            </button>

            <div v-if="filteredProducts.length === 0" class="col-span-full text-center py-12">
              <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <p class="text-gray-500">No products found</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart Section -->
      <div class="lg:w-96 flex flex-col bg-white rounded-lg border border-gray-200 shadow-sm min-h-0">
        <!-- Cart Header -->
        <div class="flex-shrink-0 p-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Current Order</h2>
            <span class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold">
              {{ cart.itemCount }} items
            </span>
          </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-2 min-h-0">
          <div v-if="cart.items.length === 0" class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-gray-500 text-sm">Cart is empty</p>
          </div>

          <div
            v-for="item in cart.items"
            :key="item.product_id"
            class="bg-gray-50 rounded-lg p-3 border border-gray-100"
          >
            <div class="flex justify-between items-start mb-2">
              <h3 class="font-semibold text-sm flex-1 text-gray-900">{{ item.name }}</h3>
              <button
                @click="removeFromCart(item.product_id)"
                class="text-red-500 hover:text-red-700 ml-2 p-1"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <button
                  @click="updateQuantity(item.product_id, item.quantity - 1)"
                  class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-100 flex items-center justify-center transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                <span class="w-10 text-center font-semibold text-gray-900">{{ item.quantity }}</span>
                <button
                  @click="updateQuantity(item.product_id, item.quantity + 1)"
                  class="w-8 h-8 rounded-lg bg-white border border-gray-300 hover:bg-gray-100 flex items-center justify-center transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>
              <p class="font-bold text-indigo-600">Rp {{ formatPrice(item.price * item.quantity) }}</p>
            </div>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="flex-shrink-0 border-t border-gray-200 p-4 space-y-2 bg-gray-50">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold text-gray-900">Rp {{ formatPrice(cart.subtotal) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Tax (10%)</span>
            <span class="font-semibold text-gray-900">Rp {{ formatPrice(cart.tax) }}</span>
          </div>
          <div class="flex justify-between text-sm items-center">
            <span class="text-gray-600">Discount</span>
            <input
              v-model.number="discountInput"
              @input="applyDiscount"
              type="number"
              min="0"
              class="w-28 px-3 py-1.5 text-right border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
              placeholder="0"
            />
          </div>
          <div class="border-t border-gray-300 pt-2 flex justify-between items-center">
            <span class="font-bold text-base text-gray-900">Total</span>
            <span class="font-bold text-2xl text-indigo-600">Rp {{ formatPrice(cart.grandTotal) }}</span>
          </div>
        </div>

        <!-- Checkout Button -->
        <div class="flex-shrink-0 p-4 border-t border-gray-200">
          <button
            @click="openCheckout"
            :disabled="cart.items.length === 0"
            class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-bold hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md"
          >
            Checkout Now
          </button>
        </div>
      </div>
    </div>

    <!-- Checkout Modal -->
    <CheckoutModal
      v-if="showCheckoutModal"
      :cart-data="cart.getCartData()"
      @close="showCheckoutModal = false"
      @success="handleCheckoutSuccess"
    />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CheckoutModal from '@/Components/CheckoutModal.vue';
import { useCartStore } from '@/stores/cartStore';
import { ref, computed } from 'vue';

const props = defineProps({
  categories: Array,
  products: Array,
});

const cart = useCartStore();
const selectedCategory = ref(null);
const searchQuery = ref('');
const discountInput = ref(0);
const showCheckoutModal = ref(false);

const filteredProducts = computed(() => {
  let filtered = props.products;

  if (selectedCategory.value) {
    filtered = filtered.filter(p => p.category_id === selectedCategory.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(p =>
      p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  return filtered;
});

const addToCart = (product) => {
  try {
    cart.addItem(product);
  } catch (error) {
    alert(error.message);
  }
};

const updateQuantity = (productId, quantity) => {
  try {
    cart.updateQuantity(productId, quantity);
  } catch (error) {
    alert(error.message);
  }
};

const removeFromCart = (productId) => {
  cart.removeItem(productId);
};

const applyDiscount = () => {
  cart.applyDiscount(discountInput.value || 0);
};

const openCheckout = () => {
  if (cart.items.length > 0) {
    showCheckoutModal.value = true;
  }
};

const handleCheckoutSuccess = () => {
  showCheckoutModal.value = false;
  discountInput.value = 0;
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
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
</style>
