<script setup>
import { computed, onMounted, ref } from "vue";
import { useCartStore } from "../../stores/cart";

const cart = useCartStore();
const checkoutFeedback = ref("");

const formattedTotal = computed(() =>
  new Intl.NumberFormat("nl-NL", {
    style: "currency",
    currency: "EUR",
  }).format(cart.totalPrice)
);

onMounted(() => {
  cart.loadCart();
});

async function increase(item) {
  checkoutFeedback.value = "";

  if (item.product.stock <= 0) {
    alert("No more stock is available for this product.");
    return;
  }

  const didUpdate = await cart.updateQuantity(item.product.id, item.quantity + 1);
  if (!didUpdate && cart.errorMessage) {
    alert(cart.errorMessage);
  }
}

async function decrease(item) {
  checkoutFeedback.value = "";

  if (item.quantity > 1) {
    const didUpdate = await cart.updateQuantity(item.product.id, item.quantity - 1);
    if (!didUpdate && cart.errorMessage) {
      alert(cart.errorMessage);
    }
  } else {
    const didRemove = await cart.removeFromCart(item.product.id);
    if (!didRemove && cart.errorMessage) {
      alert(cart.errorMessage);
    }
  }
}

async function removeItem(productId) {
  checkoutFeedback.value = "";
  const didRemove = await cart.removeFromCart(productId);
  if (!didRemove && cart.errorMessage) {
    alert(cart.errorMessage);
  }
}

async function checkout() {
  const result = await cart.checkout();
  checkoutFeedback.value = result.message;

  if (!result.success) {
    alert(result.message);
  }
}
</script>

<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="page-hero">
        <div>
          <span class="pill">Cart</span>
          <h1 class="section-heading__title mt-3">Reserved hardware, ready for checkout.</h1>
          <p class="section-heading__copy mb-0">
            Cart quantities stay synchronized with backend inventory to keep ordering realistic.
          </p>
        </div>
      </div>

      <div v-if="checkoutFeedback" class="alert" :class="cart.items.length === 0 ? 'alert-success' : 'alert-info'">
        {{ checkoutFeedback }}
      </div>

      <div v-if="cart.errorMessage" class="alert alert-danger">
        {{ cart.errorMessage }}
      </div>

      <div v-if="cart.isLoading" class="text-center my-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div v-else-if="cart.items.length === 0" class="empty-panel">
        <h2>Your cart is currently empty.</h2>
        <p class="mb-0">Browse the catalogue and add a few components to test the order flow.</p>
        <router-link to="/products" class="btn btn-primary mt-3">Browse products</router-link>
      </div>

      <div v-else class="cart-layout">
        <div class="cart-items">
          <article v-for="item in cart.items" :key="item.product.id" class="cart-card">
            <img :src="item.product.image" :alt="item.product.name" class="cart-card__image" />
            <div class="cart-card__content">
              <p class="product-tile__category mb-2">{{ item.product.category.name }}</p>
              <h2 class="h5 mb-2">{{ item.product.name }}</h2>
              <p class="text-muted mb-3">Available stock remaining: {{ item.product.stock }}</p>
              <div class="cart-card__meta">
                <span>{{ item.quantity }} x €{{ Number(item.product.price).toFixed(2) }}</span>
                <strong>€{{ (item.product.price * item.quantity).toFixed(2) }}</strong>
              </div>
            </div>
            <div class="cart-card__actions">
              <button class="btn btn-outline-light btn-sm" @click="increase(item)">+</button>
              <button class="btn btn-outline-light btn-sm" @click="decrease(item)">-</button>
              <button class="btn btn-outline-danger btn-sm" @click="removeItem(item.product.id)">
                Remove
              </button>
            </div>
          </article>
        </div>

        <aside class="cart-summary">
          <span class="pill">Order summary</span>
          <div class="cart-line mt-4">
            <span>Total items</span>
            <strong>{{ cart.totalItems }}</strong>
          </div>
          <div class="cart-line">
            <span>Delivery</span>
            <strong>Free</strong>
          </div>
          <div class="cart-line cart-line--total">
            <span>Total</span>
            <strong>{{ formattedTotal }}</strong>
          </div>
          <button class="btn btn-primary btn-lg w-100 mt-3" @click="checkout">Checkout</button>
        </aside>
      </div>
    </div>
  </section>
</template>
