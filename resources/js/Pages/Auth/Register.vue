<template>
  <div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-gray-900">POS System</h1>
          <p class="text-gray-600 mt-2">Register your company</p>
        </div>

        <form @submit.prevent="submit">
          <div class="space-y-5">
            <!-- Company Information -->
            <div class="border-b pb-4 mb-4">
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Company Information</h3>
              
              <div class="space-y-4">
                <div>
                  <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Company Name
                  </label>
                  <input
                    id="company_name"
                    v-model="form.company_name"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="My Company"
                  />
                  <p v-if="form.errors.company_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.company_name }}
                  </p>
                </div>

                <div>
                  <label for="company_email" class="block text-sm font-medium text-gray-700 mb-1">
                    Company Email
                  </label>
                  <input
                    id="company_email"
                    v-model="form.company_email"
                    type="email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="contact@company.com"
                  />
                  <p v-if="form.errors.company_email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.company_email }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Owner Information -->
            <div>
              <h3 class="text-sm font-semibold text-gray-700 mb-3">Owner Account</h3>
              
              <div class="space-y-4">
                <div>
                  <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Owner Name
                  </label>
                  <input
                    id="owner_name"
                    v-model="form.owner_name"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="John Doe"
                  />
                  <p v-if="form.errors.owner_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.owner_name }}
                  </p>
                </div>

                <div>
                  <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-1">
                    Owner Email
                  </label>
                  <input
                    id="owner_email"
                    v-model="form.owner_email"
                    type="email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="owner@company.com"
                  />
                  <p v-if="form.errors.owner_email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.owner_email }}
                  </p>
                </div>

                <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                  </label>
                  <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                  />
                  <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                    {{ form.errors.password }}
                  </p>
                </div>

                <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                  </label>
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                    placeholder="••••••••"
                  />
                </div>
              </div>
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="!form.processing">Register Company</span>
              <span v-else>Registering...</span>
            </button>
          </div>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <a href="/login" class="text-indigo-600 hover:text-indigo-700 font-medium">Sign in</a>
          </p>
        </div>

        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
          <p class="text-xs text-blue-800">
            <strong>Note:</strong> After registration, 3 users will be created automatically:
            Owner (you), Admin, and Cashier with default password "password".
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  company_name: '',
  company_email: '',
  owner_name: '',
  owner_email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
