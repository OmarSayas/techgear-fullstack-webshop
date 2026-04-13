<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="login-layout">
        <div class="login-panel login-panel--accent">
          <span class="pill">Demo access</span>
          <h1 class="section-heading__title mt-3">
            Explore customer and admin flows without creating seed data from scratch.
          </h1>
          <p class="section-heading__copy">
            Use the built-in demo accounts to review route guards, cart behavior, order history,
            and admin management screens. You can also create a new user account from here.
          </p>

          <div class="credential-card__grid">
            <button
              v-for="account in demoAccounts"
              :key="account.label"
              type="button"
              class="demo-button"
              @click="fillDemoAccount(account)"
            >
              <strong>{{ account.label }}</strong>
              <span>{{ account.username }} / {{ account.password }}</span>
            </button>
          </div>
        </div>

        <div class="login-panel">
          <span class="pill">{{ isSignup ? "Create account" : "Welcome back" }}</span>
          <h2 class="section-heading__title mt-3">{{ isSignup ? "Sign up" : "Login" }}</h2>

          <form class="mt-4" @submit.prevent="isSignup ? handleSignup() : handleLogin()">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" id="username" v-model="username" class="form-control" required />
            </div>

            <div class="mb-3" v-if="isSignup">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" v-model="email" class="form-control" required />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" id="password" v-model="password" class="form-control" required />
              <small v-if="isSignup" class="form-text text-muted">
                Minimum 8 characters, with uppercase, lowercase, and a number.
              </small>
            </div>

            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ isSignup ? "Create account" : "Login" }}
            </button>
          </form>

          <button @click="toggleMode" class="btn btn-link px-0 mt-3">
            {{ isSignup ? "Already have an account? Login here" : "Don't have an account? Sign up here" }}
          </button>

          <div v-if="error" class="alert alert-danger mt-3">
            {{ error }}
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "../../axios-auth";
import { useAuthStore } from "../../stores/auth";

const username = ref("");
const password = ref("");
const email = ref("");
const error = ref(null);
const isSignup = ref(false);
const loading = ref(false);
const auth = useAuthStore();
const router = useRouter();
const route = useRoute();

const demoAccounts = [
  { label: "Admin demo", username: "admin", password: "Admin123" },
  { label: "User demo", username: "username", password: "Password123" },
];

function toggleMode() {
  isSignup.value = !isSignup.value;
  error.value = null;
}

function fillDemoAccount(account) {
  username.value = account.username;
  password.value = account.password;
  isSignup.value = false;
  error.value = null;
}

async function handleLogin() {
  error.value = null;
  loading.value = true;

  try {
    const response = await axios.post("/users/login", {
      username: username.value,
      password: password.value,
    });

    auth.setToken(response.data.token);
    localStorage.setItem("refreshToken", response.data.refreshToken);

    await router.push(route.query.redirect || "/products");
  } catch (err) {
    error.value = err.response?.data?.errorMessage || "Login failed. Please try again.";
    console.error("Login error:", err);
  } finally {
    loading.value = false;
  }
}

async function handleSignup() {
  error.value = null;
  loading.value = true;

  const strongPasswordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/;
  if (!strongPasswordPattern.test(password.value)) {
    error.value =
      "Password must be at least 8 characters long and contain uppercase, lowercase, and numeric characters.";
    loading.value = false;
    return;
  }

  try {
    await axios.post("/users/signup", {
      username: username.value,
      password: password.value,
      email: email.value,
    });

    await handleLogin();
  } catch (err) {
    error.value = err.response?.data?.errorMessage || "Signup failed. Please try again.";
    console.error("Signup error:", err);
  } finally {
    loading.value = false;
  }
}
</script>
