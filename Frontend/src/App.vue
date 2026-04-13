<script setup>
import { RouterView } from 'vue-router'
import Navigation from './components/Navigation.vue'


</script>

<template>
  <div class="app-shell">
    <div class="app-shell__glow app-shell__glow--left"></div>
    <div class="app-shell__glow app-shell__glow--right"></div>
    <Navigation />
    <main class="app-shell__main">
      <RouterView />
    </main>
  </div>
</template>

<style scoped></style>
