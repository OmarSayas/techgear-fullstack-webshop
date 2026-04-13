<template>
  <section class="section-shell">
    <div class="container">
      <div class="empty-panel">
        <span class="pill">404</span>
        <h1 class="section-heading__title mt-3">That page does not exist.</h1>
        <p class="section-heading__copy">
          The route may have moved, or the URL may be incorrect. You can head back to the
          product catalogue or the home page from here.
        </p>
        <div class="hero__actions justify-content-center">
          <router-link to="/products" class="btn btn-primary">Browse products</router-link>
          <router-link to="/" class="btn btn-outline-light">Back home</router-link>
        </div>
      </div>
    </div>
  </section>
</template>
