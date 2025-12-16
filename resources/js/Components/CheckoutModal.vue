<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Checkout</h2>
      </div>

      <!-- Order Summary -->
      <div class="p-6 border-b border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3">Order Summary</h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold">Rp {{ formatPrice(cartData.subtotal) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-600">Tax (10%)</span>
            <span class="font-semibold">Rp {{ formatPrice(cartData.tax) }}</span>
          </div>
          <div v-if="cartData.discount > 0" class="flex justify-between text-green-600">
            <span>Discount</span>
            <span class="font-semibold">- Rp {{ formatPrice(cartData.discount) }}</span>
          </div>
          <div class="border-t border-gray-200 pt-2 flex justify-between">
            <span class="font-bold text-lg">Grand Total</span>
            <span class="font-bold text-2xl text-indigo-600">Rp {{ formatPrice(cartData.grand_total) }}</span>
          </div>
        </div>
      </div>

      <!-- Payment Method -->
      <div class="p-6 border-b border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3">Payment Method</h3>
        <div class="space-y-2">
          <label
            v-for="method in paymentMethods"
            :key="method.value"
            class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition"
            :class="form.payment_method === method.value ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
          >
            <input
              v-model="form.payment_method"
              type="radio"
              :value="method.value"
              class="w-4 h-4 text-indigo-600"
            />
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <component :is="method.icon" class="w-5 h-5 text-gray-600" />
                <span class="font-medium">{{ method.label }}</span>
              </div>
            </div>
          </label>
        </div>
        <p v-if="form.errors.payment_method" class="mt-2 text-sm text-red-600">
          {{ form.errors.payment_method }}
        </p>
      </div>

      <!-- Actions -->
      <div class="p-6 flex gap-3">
        <button
          @click="$emit('close')"
          type="button"
          class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
        >
          Cancel
        </button>
        <button
          @click="processCheckout"
          :disabled="form.processing || !form.payment_method"
          class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-bold hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ form.processing ? 'Processing...' : 'Confirm Payment' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useCartStore } from '@/stores/cartStore';
import { ref } from 'vue';

const props = defineProps({
  cartData: Object,
});

const emit = defineEmits(['close', 'success']);

const cart = useCartStore();

const form = useForm({
  items: props.cartData.items,
  subtotal: props.cartData.subtotal,
  tax: props.cartData.tax,
  discount: props.cartData.discount,
  grand_total: props.cartData.grand_total,
  payment_method: '',
});

const paymentMethods = [
  {
    value: 'cash',
    label: 'Cash',
    icon: {
      template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>'
    }
  },
  {
    value: 'qris',
    label: 'QRIS',
    icon: {
      template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>'
    }
  },
  {
    value: 'ewallet',
    label: 'E-Wallet',
    icon: {
      template: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>'
    }
  },
];

const processCheckout = () => {
  form.post('/orders', {
    onSuccess: () => {
      cart.clearCart();
      emit('success');
      emit('close');
    },
    onError: (errors) => {
      console.error('Checkout error:', errors);
    },
  });
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};
</script>
