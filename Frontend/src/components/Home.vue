<script setup>
const highlights = [
  { value: "Vue 3", label: "Reactive storefront with Pinia state management" },
  { value: "PHP API", label: "JWT auth, role-based access, and cart workflows" },
  { value: "MariaDB", label: "Persistent products, users, carts, and orders" },
];

const features = [
  {
    title: "Role-aware user journeys",
    description:
      "Customers can browse, cart, and order, while admins manage inventory, categories, and users from the same application.",
  },
  {
    title: "Inventory-aware cart flow",
    description:
      "Products respect stock availability across add-to-cart, quantity updates, and checkout so the data layer stays realistic.",
  },
  {
    title: "Dockerized backend setup",
    description:
      "The PHP, Nginx, MariaDB, and phpMyAdmin services are ready to run locally with a documented workflow.",
  },
];

const demoAccounts = [
  { role: "Admin demo", username: "admin", password: "Admin123" },
  { role: "User demo", username: "username", password: "Password123" },
];

const stack = ["Vue 3", "Pinia", "Vue Router", "Axios", "PHP", "MariaDB", "Docker", "Nginx"];
</script>

<template>
  <section class="hero">
    <div class="container">
      <div class="hero__grid">
        <div class="hero__panel">
          <p class="hero__eyebrow">TechGear commerce platform</p>
          <h1 class="hero__title">A PC webshop with admin tooling, cart logic, and JWT auth.</h1>
          <p class="hero__copy">
            This project showcases full-stack product thinking: customer browsing flows, admin
            management tools, protected routes, and a backend API that handles authentication,
            orders, categories, and stock-aware cart behavior.
          </p>
          <div class="hero__actions">
            <router-link to="/products" class="btn btn-primary btn-lg">Explore products</router-link>
            <router-link to="/login" class="btn btn-outline-light btn-lg">Test demo accounts</router-link>
          </div>
        </div>

        <div class="hero__panel hero__panel--contrast">
          <p class="hero__eyebrow">Used stack</p>
          <div class="hero__metrics">
            <article v-for="item in highlights" :key="item.value" class="metric-card">
              <strong>{{ item.value }}</strong>
              <span>{{ item.label }}</span>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-shell">
    <div class="container">
      <div class="section-heading">
        <span class="pill">Project highlights</span>
        <h2 class="section-heading__title">Built to demonstrate both product polish and backend reasoning.</h2>
      </div>

      <div class="feature-grid">
        <article v-for="feature in features" :key="feature.title" class="feature-card">
          <h3>{{ feature.title }}</h3>
          <p>{{ feature.description }}</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="row g-4 align-items-stretch">
        <div class="col-lg-6">
          <div class="credential-card h-100">
            <span class="pill">Demo credentials</span>
            <h2 class="section-heading__title mt-3">Quick ways for reviewers to explore the app.</h2>
            <div class="credential-card__grid">
              <article v-for="account in demoAccounts" :key="account.role" class="credential-card__item">
                <h3>{{ account.role }}</h3>
                <p><strong>Username:</strong> {{ account.username }}</p>
                <p><strong>Password:</strong> {{ account.password }}</p>
              </article>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="credential-card h-100">
            <span class="pill">Core stack</span>
            <h2 class="section-heading__title mt-3">Technology choices kept intentionally lightweight.</h2>
            <div class="stack-list">
              <span v-for="item in stack" :key="item" class="stack-pill">{{ item }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
