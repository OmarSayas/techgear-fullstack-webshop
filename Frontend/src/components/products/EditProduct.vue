<script setup>
import { ref, defineProps, onMounted } from 'vue'
import axios from '../../axios-auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const isLoading = ref(false)
const errorMessage = ref("")

const product = ref({
  id: 0,
  name: "",
  price: "",
  description: "",
  image: "",
  category_id: 0,
  stock: 0,
})

const categories = ref([])

const props = defineProps({
  id: Number
})

onMounted(() => {
  fetchCategories()
  fetchProduct()
})

async function fetchCategories() {
  isLoading.value = true
  try {
    const res = await axios.get('http://localhost/categories')
    categories.value = res.data
  } catch (err) {
    errorMessage.value = "Failed to load categories"
  } finally {
    isLoading.value = false
  }
}

async function fetchProduct() {
  try {
    const res = await axios.get(`http://localhost/products/${props.id}`)
    product.value = res.data
  } catch (err) {
    errorMessage.value = "Failed to load product"
  }
}

async function putProduct() {
  if (product.value.price < 0 || product.value.stock < 0) {
    errorMessage.value = 'Price and stock must be 0 or more.'
    return
  }

  isLoading.value = true
  try {
    await axios.put(`/products/${props.id}`, product.value)
    router.push('/products')
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || 'Failed to update product'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <section>
    <div class="container">
      <form>
        <h2 class="mt-3 mt-lg-5">Edit Product</h2>

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
            <option value="0" disabled selected>Select a category</option>
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
          <button type="button" class="btn btn-primary" @click="putProduct" :disabled="isLoading">
            {{ isLoading ? 'Saving...' : 'Save Changes' }}
          </button>
          <button type="button" class="btn btn-danger ms-2" @click="$router.push('/products')">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </section>
</template>
