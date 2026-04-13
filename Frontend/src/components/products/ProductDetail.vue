<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import axios from "../../axios-auth";
import { useAuthStore } from "../../stores/auth";
import { useCartStore } from "../../stores/cart";

const route = useRoute();
const auth = useAuthStore();
const cart = useCartStore();

const product = ref(null);
const errorMessage = ref("");
const isLoading = ref(false);

async function fetchProduct() {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    const response = await axios.get(`/products/${route.params.id}`);
    product.value = response.data;
  } catch (error) {
    errorMessage.value = "Failed to load product.";
    console.error(error);
  } finally {
    isLoading.value = false;
  }
}

async function addToCart() {
  const didAdd = await cart.addToCart(product.value);

  if (didAdd && product.value.stock > 0) {
    product.value.stock -= 1;
  }

  if (!didAdd && cart.errorMessage) {
    alert(cart.errorMessage);
  }
}

const formattedPrice = computed(() => {
  const price = Number(product.value?.price);
  return new Intl.NumberFormat("nl-NL", {
    style: "currency",
    currency: "EUR",
  }).format(isNaN(price) ? 0 : price);
});

const stockLabel = computed(() => {
  if (!product.value) return "";
  if (product.value.stock === 0) return "Sold out";
  if (product.value.stock <= 5) return "Low stock";
  return "Ready to ship";
});

onMounted(fetchProduct);
watch(() => route.params.id, fetchProduct);
</script>

<template>
  <div class="container" v-if="auth.role !== 'admin'">
    <div v-if="isLoading" class="text-center my-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div v-else-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>

    <div v-else-if="product" class="detail-layout">
      <article class="detail-card">
        <div class="detail-card__media">
          <img :src="product.image" :alt="product.name" class="detail-card__image" />
        </div>

        <div class="detail-card__content">
          <span class="pill">{{ product.category.name }}</span>
          <h1 class="section-heading__title mt-3">{{ product.name }}</h1>
          <p class="detail-card__description">
            {{ product.description || "A carefully selected hardware option designed for performance-focused buyers." }}
          </p>

          <div class="detail-card__facts">
            <div>
              <span>Price</span>
              <strong>{{ formattedPrice }}</strong>
            </div>
            <div>
              <span>Availability</span>
              <strong>{{ stockLabel }}</strong>
            </div>
            <div>
              <span>Units remaining</span>
              <strong>{{ product.stock }}</strong>
            </div>
          </div>

          <button
            v-if="auth.isLoggedIn"
            class="btn btn-primary btn-lg mt-4"
            :disabled="product.stock <= 0"
            @click="addToCart"
          >
            {{ product.stock <= 0 ? "Out of stock" : "Add to cart" }}
          </button>
          <router-link v-else to="/login" class="btn btn-outline-light btn-lg mt-4">
            Login to order
          </router-link>
        </div>
      </article>
    </div>
  </div>

  <div v-else class="container">
    <div class="empty-panel">
      <h2>This page is reserved for shoppers.</h2>
      <p class="mb-0">Admins can manage this item from the catalogue dashboard instead.</p>
      <router-link to="/products" class="btn btn-primary mt-3">Back to products</router-link>
    </div>
  </div>
</template>
