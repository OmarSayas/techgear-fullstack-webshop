<script setup>
import { computed, ref, watch } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { useCartStore } from "../stores/cart";

const auth = useAuthStore();
const cart = useCartStore();
const router = useRouter();
const route = useRoute();
const isMenuOpen = ref(false);

watch(
  () => route.fullPath,
  () => {
    isMenuOpen.value = false;
  }
);

const primaryLinks = computed(() => {
  const links = [
    { to: "/", label: "Home" },
    { to: "/products", label: "Products" },
  ];

  if (auth.role === "admin") {
    links.push({ to: "/admin/users", label: "Users" });
    links.push({ to: "/admin/categories", label: "Categories" });
  }

  if (auth.role === "user") {
    links.push({ to: "/orders", label: "Orders" });
  }

  return links;
});

const accountLabel = computed(() => {
  if (!auth.isLoggedIn) {
    return "Guest session";
  }

  return `${auth.username ?? "Signed in"} · ${auth.role}`;
});

function logout() {
  auth.logout();
  cart.resetCart();
  router.push("/login");
}

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
</script>

<template>
  <nav class="site-nav">
    <div class="container site-nav__inner">
      <RouterLink to="/" class="site-nav__brand">
        <span class="site-nav__brand-mark">TG</span>
        <span>
          <strong>TechGear</strong>
          <small>webshop</small>
        </span>
      </RouterLink>

      <button
        class="site-nav__toggle"
        type="button"
        :aria-expanded="isMenuOpen"
        aria-label="Toggle navigation"
        @click="toggleMenu"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>

      <div :class="['site-nav__menu', { 'site-nav__menu--open': isMenuOpen }]">
        <div class="site-nav__links">
          <RouterLink
            v-for="link in primaryLinks"
            :key="link.to"
            :to="link.to"
            class="site-nav__link"
            active-class="site-nav__link--active"
          >
            {{ link.label }}
          </RouterLink>
        </div>

        <div class="site-nav__actions">
          <div class="site-nav__status">
            <span class="site-nav__status-dot"></span>
            {{ accountLabel }}
          </div>

          <RouterLink
            v-if="auth.role === 'user'"
            to="/cart"
            class="site-nav__link site-nav__link--compact"
            active-class="site-nav__link--active"
          >
            Cart
            <span class="site-nav__badge">{{ cart.totalItems }}</span>
          </RouterLink>

          <RouterLink
            v-if="!auth.isLoggedIn"
            to="/login"
            class="btn btn-primary btn-sm px-3"
          >
            Login
          </RouterLink>

          <button v-else class="btn btn-outline-light btn-sm px-3" @click="logout">
            Logout
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>
