<script setup>
import { computed, defineEmits, defineProps, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";
import { useCartStore } from "../../stores/cart";
import axios from "../../axios-auth";

const props = defineProps({
  product: Object,
  isAdmin: Boolean,
});

const emit = defineEmits(["productDeleted"]);

const cart = useCartStore();
const auth = useAuthStore();
const router = useRouter();
const isDeleting = ref(false);

const formattedPrice = computed(() => {
  const price = Number(props.product.price);
  return new Intl.NumberFormat("nl-NL", {
    style: "currency",
    currency: "EUR",
  }).format(isNaN(price) ? 0 : price);
});

const imageSource = computed(() => {
  if (props.product.image) {
    return props.product.image;
  }

  return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 480'%3E%3Crect width='640' height='480' rx='32' fill='%23111827'/%3E%3Cpath d='M196 318h248' stroke='%235eead4' stroke-width='22' stroke-linecap='round'/%3E%3Cpath d='M242 210h156' stroke='%23f59e0b' stroke-width='22' stroke-linecap='round'/%3E%3Ccircle cx='320' cy='264' r='28' fill='%235eead4'/%3E%3C/svg%3E";
});

const shortDescription = computed(() => {
  const description =
    props.product.description ||
    "Premium hardware picked for performance-focused buyers.";
  return description.length > 120 ? `${description.slice(0, 117)}...` : description;
});

const stockLabel = computed(() => {
  if (props.product.stock === 0) return "Sold out";
  if (props.product.stock <= 5) return "Low stock";
  return "In stock";
});

const stockClass = computed(() => {
  if (props.product.stock === 0) return "stock-chip--empty";
  if (props.product.stock <= 5) return "stock-chip--low";
  return "stock-chip--in";
});

async function deleteProduct(id) {
  try {
    isDeleting.value = true;
    await axios.delete(`/products/${id}`);
    emit("productDeleted");
  } catch (error) {
    alert(error.response?.data?.errorMessage || "Failed to delete product");
    console.error("Failed to delete product:", error);
  } finally {
    isDeleting.value = false;
  }
}

function editProduct(id) {
  router.push(`/editproduct/${id}`);
}

function goToDetail() {
  if (!props.isAdmin) {
    router.push(`/product/${props.product.id}`);
  }
}

async function addToCart() {
  const didAdd = await cart.addToCart(props.product);

  if (didAdd && props.product.stock > 0) {
    props.product.stock -= 1;
  }

  if (!didAdd && cart.errorMessage) {
    alert(cart.errorMessage);
  }
}
</script>

<template>
  <article
    :class="['product-tile', isDeleting ? 'opacity-50 pointer-events-none' : '']"
    @click="goToDetail"
  >
    <div class="product-tile__media">
      <img :src="imageSource" :alt="product.name" class="product-tile__image" />
      <span :class="['stock-chip', stockClass]">{{ stockLabel }}</span>
    </div>

    <div class="product-tile__body">
      <p class="product-tile__category">{{ product.category.name }}</p>
      <h3 class="product-tile__title">{{ product.name }}</h3>
      <p class="product-tile__description">{{ shortDescription }}</p>
      <div class="product-tile__meta">
        <span>{{ product.stock }} left in stock</span>
        <strong>{{ formattedPrice }}</strong>
      </div>
    </div>

    <div class="product-tile__footer" @click.stop>
      <template v-if="isAdmin">
        <button class="btn btn-outline-light btn-sm" @click="editProduct(product.id)">Edit</button>
        <button class="btn btn-danger btn-sm" @click="deleteProduct(product.id)">Delete</button>
      </template>

      <template v-else-if="auth.isLoggedIn">
        <button class="btn btn-primary w-100" :disabled="product.stock === 0" @click="addToCart">
          {{ product.stock === 0 ? "Out of stock" : "Add to cart" }}
        </button>
      </template>

      <router-link v-else :to="`/product/${product.id}`" class="btn btn-outline-light w-100">
        View details
      </router-link>
    </div>
  </article>
</template>
