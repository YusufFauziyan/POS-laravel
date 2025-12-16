<template>
  <AppLayout title="Create Product">
    <div class="max-w-3xl">
      <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form @submit.prevent="submit">
          <div class="space-y-5">
            <!-- Product Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Product Name *
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="e.g., Cappuccino"
              />
              <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Category -->
            <div>
              <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                Category *
              </label>
              <select
                id="category_id"
                v-model="form.category_id"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              >
                <option value="">Select a category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.category_id }}
              </p>
            </div>

            <!-- Price and Cost -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                  Price *
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                  <input
                    id="price"
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="0"
                  />
                </div>
                <p v-if="form.errors.price" class="mt-1 text-sm text-red-600">
                  {{ form.errors.price }}
                </p>
              </div>

              <div>
                <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">
                  Cost (Optional)
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                  <input
                    id="cost"
                    v-model="form.cost"
                    type="number"
                    step="0.01"
                    min="0"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    placeholder="0"
                  />
                </div>
                <p v-if="form.errors.cost" class="mt-1 text-sm text-red-600">
                  {{ form.errors.cost }}
                </p>
              </div>
            </div>

            <!-- Stock -->
            <div>
              <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">
                Stock *
              </label>
              <input
                id="stock"
                v-model="form.stock"
                type="number"
                min="0"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                placeholder="0"
              />
              <p v-if="form.errors.stock" class="mt-1 text-sm text-red-600">
                {{ form.errors.stock }}
              </p>
            </div>

            <!-- Image Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Product Image
              </label>
              <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition">
                <div class="space-y-1 text-center">
                  <div v-if="imagePreview" class="mb-4">
                    <img :src="imagePreview" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg" />
                    <button
                      type="button"
                      @click="removeImage"
                      class="mt-2 text-sm text-red-600 hover:text-red-800"
                    >
                      Remove
                    </button>
                  </div>
                  <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <div class="flex text-sm text-gray-600">
                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                      <span>Upload a file</span>
                      <input
                        id="image"
                        type="file"
                        accept="image/jpeg,image/jpg,image/png"
                        class="sr-only"
                        @change="handleImageUpload"
                      />
                    </label>
                    <p class="pl-1">or drag and drop</p>
                  </div>
                  <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                </div>
              </div>
              <p v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                {{ form.errors.image }}
              </p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
              <input
                id="is_active"
                v-model="form.is_active"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="is_active" class="ml-2 block text-sm text-gray-700">
                Active
              </label>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end space-x-3">
            <Link
              href="/products"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
            >
              Create Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  categories: Array,
});

const form = useForm({
  name: '',
  category_id: '',
  price: '',
  cost: '',
  stock: 0,
  image: null,
  is_active: true,
});

const imagePreview = ref(null);

const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removeImage = () => {
  form.image = null;
  imagePreview.value = null;
};

const submit = () => {
  form.post('/products');
};
</script>
