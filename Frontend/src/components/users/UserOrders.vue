<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="page-hero">
        <div>
          <span class="pill">Orders</span>
          <h1 class="section-heading__title mt-3">My orders</h1>
          <p class="section-heading__copy mb-0">
            Review previous purchases with clear pricing, quantities, and totals.
          </p>
        </div>
      </div>

      <div v-if="orders.length === 0" class="empty-panel">
        <h2>No orders found yet.</h2>
        <p class="mb-0">Place an order from the cart and it will show up here.</p>
      </div>

      <div v-else class="orders-list">
        <article v-for="order in orders" :key="order.id" class="order-panel">
          <header class="order-panel__header">
            <div>
              <p class="product-tile__category mb-2">Order #{{ order.id }}</p>
              <h2 class="h4 mb-0">{{ new Date(order.created_at).toLocaleString() }}</h2>
            </div>
            <div class="order-panel__total">
              <span>Total paid</span>
              <strong>€{{ order.total_price.toFixed(2) }}</strong>
            </div>
          </header>

          <div class="order-items">
            <article v-for="item in order.items" :key="`${order.id}-${item.product.name}`" class="order-item">
              <img :src="item.product.image" :alt="item.product.name" class="order-item__image" />

              <div class="order-item__content">
                <h3 class="h5 mb-2">{{ item.product.name }}</h3>
                <div class="order-item__meta">
                  <span>Quantity</span>
                  <strong>{{ item.quantity }}</strong>
                </div>
                <div class="order-item__meta">
                  <span>Price each</span>
                  <strong>€{{ item.price_each.toFixed(2) }}</strong>
                </div>
                <div class="order-item__meta">
                  <span>Subtotal</span>
                  <strong>€{{ (item.price_each * item.quantity).toFixed(2) }}</strong>
                </div>
              </div>
            </article>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "../../axios-auth";

const orders = ref([]);

onMounted(async () => {
  try {
    const res = await axios.get("/orders");
    orders.value = res.data;
  } catch (error) {
    console.error("Failed to fetch orders:", error);
  }
});
</script>
