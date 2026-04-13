<script setup>
import ProductListItem from "./ProductListItem.vue";
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "../../axios-auth";
import { useAuthStore } from "../../stores/auth";

const auth = useAuthStore();
const router = useRouter();
const products = ref([]);
const isLoading = ref(false);
const errorMessage = ref("");
const searchTerm = ref("");
const categoryFilter = ref("all");
const availabilityFilter = ref("all");
const sortBy = ref("featured");

const categories = computed(() =>
  [...new Set(products.value.map((product) => product.category?.name).filter(Boolean))].sort()
);

const filteredProducts = computed(() => {
  let list = [...products.value];
  const normalizedSearch = searchTerm.value.trim().toLowerCase();

  if (normalizedSearch) {
    list = list.filter((product) => {
      const haystack = [product.name, product.description, product.category?.name]
        .filter(Boolean)
        .join(" ")
        .toLowerCase();

      return haystack.includes(normalizedSearch);
    });
  }

  if (categoryFilter.value !== "all") {
    list = list.filter((product) => product.category?.name === categoryFilter.value);
  }

  if (availabilityFilter.value === "in-stock") {
    list = list.filter((product) => product.stock > 0);
  }

  if (availabilityFilter.value === "low-stock") {
    list = list.filter((product) => product.stock > 0 && product.stock <= 5);
  }

  if (availabilityFilter.value === "sold-out") {
    list = list.filter((product) => product.stock === 0);
  }

  if (sortBy.value === "price-asc") {
    list.sort((a, b) => Number(a.price) - Number(b.price));
  }

  if (sortBy.value === "price-desc") {
    list.sort((a, b) => Number(b.price) - Number(a.price));
  }

  if (sortBy.value === "name") {
    list.sort((a, b) => a.name.localeCompare(b.name));
  }

  if (sortBy.value === "stock") {
    list.sort((a, b) => b.stock - a.stock);
  }

  return list;
});

const summaryStats = computed(() => [
  { label: "Products", value: products.value.length },
  { label: "In stock", value: products.value.filter((product) => product.stock > 0).length },
  { label: "Categories", value: categories.value.length },
]);

async function fetchProducts() {
  try {
    const response = await axios.get("/products");
    products.value = response.data;
    errorMessage.value = "";
  } catch (error) {
    errorMessage.value = error.response?.data?.errorMessage || "Failed to load products";
  }
}

async function loadProducts() {
  isLoading.value = true;
  try {
    await fetchProducts();
  } finally {
    isLoading.value = false;
  }
}

async function updateProducts() {
  await fetchProducts();
}

onMounted(loadProducts);
</script>

<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="page-hero">
        <div>
          <span class="pill">Catalogue</span>
          <h1 class="section-heading__title mt-3">Browse PCs, laptops, and premium components.</h1>
          <p class="section-heading__copy mb-0">
            A product list with loading states, filtering, role-aware actions, and inventory status.
          </p>
        </div>
        <div class="stat-strip">
          <article v-for="stat in summaryStats" :key="stat.label" class="stat-card">
            <strong>{{ stat.value }}</strong>
            <span>{{ stat.label }}</span>
          </article>
        </div>
      </div>

      <div class="toolbar-panel">
        <div class="filter-grid">
          <div>
            <label class="form-label">Search</label>
            <input
              v-model="searchTerm"
              type="text"
              class="form-control"
              placeholder="Search by name, description, or category"
            />
          </div>

          <div>
            <label class="form-label">Category</label>
            <select v-model="categoryFilter" class="form-select">
              <option value="all">All categories</option>
              <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
          </div>

          <div>
            <label class="form-label">Availability</label>
            <select v-model="availabilityFilter" class="form-select">
              <option value="all">All products</option>
              <option value="in-stock">In stock</option>
              <option value="low-stock">Low stock</option>
              <option value="sold-out">Sold out</option>
            </select>
          </div>

          <div>
            <label class="form-label">Sort</label>
            <select v-model="sortBy" class="form-select">
              <option value="featured">Featured</option>
              <option value="name">Name</option>
              <option value="price-asc">Price: low to high</option>
              <option value="price-desc">Price: high to low</option>
              <option value="stock">Stock level</option>
            </select>
          </div>
        </div>

        <div v-if="auth.role === 'admin'" class="hero__actions">
          <button type="button" class="btn btn-primary" @click="router.push('/createproduct')">
            Add product
          </button>
          <button type="button" class="btn btn-outline-light" @click="router.push('/admin/categories')">
            Manage categories
          </button>
        </div>
      </div>

      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        {{ errorMessage }}
      </div>

      <div v-if="isLoading" class="text-center mt-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div v-else-if="products.length === 0" class="empty-panel">
        <h2>No products available yet.</h2>
        <p class="mb-0">Start by adding a product or checking the backend seed data.</p>
      </div>

      <div v-else-if="filteredProducts.length === 0" class="empty-panel">
        <h2>No products match these filters.</h2>
        <p class="mb-0">Try a broader search term or switch back to all categories.</p>
      </div>

      <div v-else class="products-grid">
        <ProductListItem
          v-for="product in filteredProducts"
          :key="product.id"
          :product="product"
          :isAdmin="auth.role === 'admin'"
          @productDeleted="updateProducts"
        />
      </div>
    </div>
  </section>
</template>
