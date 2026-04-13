<script setup>
import { ref, onMounted } from 'vue'
import axios from '../../axios-auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const isLoading = ref(false)
const errorMessage = ref("")

const product = ref({
  name: "",
  price: "",
  description: "",
  image: "",
  category_id: 0,
  stock: 0,
})

const categories = ref([])

onMounted(fetchCategories)

async function fetchCategories() {
  isLoading.value = true
  try {
    const response = await axios.get('http://localhost/categories')
    categories.value = response.data
  } catch (error) {
    errorMessage.value = "Failed to load categories"
  } finally {
    isLoading.value = false
  }
}

async function postProduct() {
  if (product.value.name.trim() === "" || product.value.category_id === 0) {
    errorMessage.value = "Please fill in all required fields"
    return
  }

  isLoading.value = true
  try {
    await axios.post('/products', product.value)
    router.push('/products')
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to create product'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <section>
    <div class="container">
      <form>
        <h2 class="mt-3 mt-lg-5">Create a Product</h2>

        <div class="input-group mb-3">
          <span class="input-group-text">Name</span>
          <input type="text" class="form-control" v-model="product.name" required />
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Price</span>
          <input type="number" class="form-control" v-model="product.price" min="0" required />
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Description</span>
          <textarea class="form-control" v-model="product.description" rows="3"></textarea>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Image URL</span>
          <input type="text" class="form-control" v-model="product.image" />
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Category</span>
          <select class="form-select" v-model="product.category_id" required>
            <option value="0" disabled>Select a category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Stock</span>
          <input type="number" class="form-control" v-model="product.stock" min="0" required />
        </div>

        <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

        <div class="input-group mt-4">
          <button type="button" :disabled="isLoading" @click="postProduct" class="btn btn-primary">
            {{ isLoading ? 'Creating...' : 'Create Product' }}
          </button>
          <button type="button" class="btn btn-danger ms-2" @click="$router.push('/products')">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </section>
</template>
