import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", () => {
  const token = ref(localStorage.getItem("token") || null);

  function decodeToken(value) {
    if (!value) return null;

    try {
      const base64Url = value.split(".")[1];
      const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
      const padded = base64.padEnd(
        base64.length + ((4 - (base64.length % 4)) % 4),
        "="
      );
      return JSON.parse(atob(padded));
    } catch (error) {
      console.warn("Failed to parse token:", error);
      return null;
    }
  }

  const isLoggedIn = computed(() => !!token.value);

  const payload = computed(() => decodeToken(token.value)?.data || null);

  const role = computed(() => payload.value?.role || null);
  const username = computed(() => payload.value?.username || null);
  const userId = computed(() => payload.value?.userId || null);

  function setToken(newToken) {
    token.value = newToken;
    localStorage.setItem("token", newToken);
  }

  function logout() {
    token.value = null;
    localStorage.removeItem("token");
    localStorage.removeItem("refreshToken");
  }

  return {
    token,
    isLoggedIn,
    setToken,
    logout,
    role,
    username,
    userId,
    decodeToken,
  };
});
