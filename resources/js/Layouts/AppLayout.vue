<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-gradient-to-r from-indigo-600 to-purple-700 text-white z-40 shadow-lg">
      <div class="flex items-center justify-between px-4 py-3">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-white/10">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <h1 class="text-lg font-bold">POS System</h1>
        <div class="w-10"></div>
      </div>
    </header>

    <!-- Sidebar Overlay (Mobile/Tablet) -->
    <div 
      v-if="sidebarOpen" 
      @click="sidebarOpen = false"
      class="lg:hidden fixed inset-0 bg-black/50 z-40"
    ></div>

    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white transform transition-transform duration-300 z-50',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]"
    >
      <div class="p-6">
        <h1 class="text-2xl font-bold">POS System</h1>
        <p class="text-indigo-200 text-sm mt-1">{{ $page.props.auth.user.name }}</p>
        <p class="text-indigo-300 text-xs mt-1 capitalize">{{ $page.props.auth.user.role }}</p>
      </div>

      <nav class="flex-1 px-4 py-6 space-y-2">
        <Link
          href="/dashboard"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url === '/dashboard' ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Dashboard
        </Link>

        <!-- POS - Admin & Cashier only -->
        <Link
          v-if="isAdmin || isCashier"
          href="/pos"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/pos') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          POS
        </Link>

        <!-- Orders - All roles -->
        <Link
          href="/orders"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/orders') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Orders
        </Link>

        <!-- Categories - Admin only -->
        <Link
          v-if="isAdmin"
          href="/categories"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/categories') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          Categories
        </Link>

        <!-- Products - All roles (view), Admin (manage) -->
        <Link
          href="/products"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/products') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          Products
        </Link>

        <!-- Inventory - Admin & Owner only -->
        <Link
          v-if="isAdmin || isOwner"
          href="/inventory"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/inventory') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          Inventory
        </Link>

        <!-- User Management - Admin & Owner only -->
        <Link
          v-if="isAdmin || isOwner"
          href="/users"
          @click="sidebarOpen = false"
          class="flex items-center px-4 py-3 mb-2 rounded-lg transition"
          :class="$page.url.startsWith('/users') ? 'bg-white/20' : 'hover:bg-white/10'"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          Users
        </Link>
      </nav>

      <div class="absolute bottom-0 left-0 right-0 p-4">
        <Link
          href="/logout"
          method="post"
          as="button"
          class="w-full flex items-center justify-center px-4 py-3 bg-white/10 hover:bg-white/20 rounded-lg transition"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Logout
        </Link>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 pt-14 lg:pt-0 pb-16 lg:pb-0">
      <!-- Header -->
      <header class="bg-white shadow-sm hidden lg:block">
        <div class="px-8 py-4">
          <slot name="header">
            <h2 class="text-2xl font-bold text-gray-900">
              {{ title }}
            </h2>
          </slot>
        </div>
      </header>

      <!-- Flash Messages -->
      <div v-if="showFlash" :class="flashType === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'" class="mx-4 lg:mx-8 mt-4 border-l-4 p-4 rounded">
        <p :class="flashType === 'success' ? 'text-green-700' : 'text-red-700'">{{ flashMessage }}</p>
      </div>

      <!-- Page Content -->
      <main class="p-4 lg:p-8">
        <slot />
      </main>
    </div>

    <!-- Mobile Bottom Navigation (Cashier only) -->
    <nav v-if="isCashier" class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30 shadow-lg">
      <div class="grid grid-cols-4">
        <Link
          href="/dashboard"
          class="flex flex-col items-center py-4 px-2 transition"
          :class="$page.url === '/dashboard' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50'"
        >
          <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="text-xs font-semibold">Home</span>
        </Link>

        <Link
          href="/pos"
          class="flex flex-col items-center py-4 px-2 transition relative"
          :class="$page.url.startsWith('/pos') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50'"
        >
          <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span class="text-xs font-semibold">POS</span>
        </Link>

        <Link
          href="/orders"
          class="flex flex-col items-center py-4 px-2 transition"
          :class="$page.url.startsWith('/orders') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50'"
        >
          <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <span class="text-xs font-semibold">Orders</span>
        </Link>

        <Link
          href="/products"
          class="flex flex-col items-center py-4 px-2 transition"
          :class="$page.url.startsWith('/products') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50'"
        >
          <svg class="w-7 h-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          <span class="text-xs font-semibold">Products</span>
        </Link>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

defineProps({
  title: String,
});

const page = usePage();
const showFlash = ref(false);
const flashMessage = ref('');
const flashType = ref('success');
const sidebarOpen = ref(false);

// Role checks - access from page.props.auth.user.role
const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role);
const isAdmin = computed(() => userRole.value === 'admin');
const isCashier = computed(() => userRole.value === 'cashier');
const isOwner = computed(() => userRole.value === 'owner');

watch(() => page.props.flash, (flash) => {
  if (flash?.success || flash?.error) {
    flashMessage.value = flash.success || flash.error;
    flashType.value = flash.success ? 'success' : 'error';
    showFlash.value = true;
    setTimeout(() => {
      showFlash.value = false;
    }, 3000);
  }
}, { deep: true });
</script>
