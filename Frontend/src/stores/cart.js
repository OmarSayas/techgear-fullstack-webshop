import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axios from "../axios-auth";

export const useCartStore = defineStore("cart", () => {
  const items = ref([]);
  const isLoading = ref(false);
  const errorMessage = ref("");

  const totalItems = computed(() =>
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  );

  const totalPrice = computed(() =>
    items.value.reduce(
      (sum, item) => sum + item.quantity * item.product.price,
      0
    )
  );

  function findItem(productId) {
    return items.value.find((i) => i.product.id === productId);
  }

  function getErrorMessage(error, fallback) {
    return error.response?.data?.errorMessage || fallback;
  }

  function resetCart() {
    items.value = [];
    errorMessage.value = "";
  }

  async function loadCart() {
    isLoading.value = true;
    try {
      const res = await axios.get("/cart");
      items.value = Array.isArray(res.data) ? res.data : [];
      errorMessage.value = "";
    } catch (err) {
      console.error("Failed to load cart:", err);
      items.value = [];
      errorMessage.value = getErrorMessage(err, "Failed to load cart");
    } finally {
      isLoading.value = false;
    }
  }

  async function addToCart(product) {
    try {
      await axios.post("/cart", {
        product_id: product.id,
        quantity: 1,
      });

      await loadCart();
      return true;
    } catch (err) {
      console.error("Failed to add to cart:", err);
      errorMessage.value = getErrorMessage(err, "Failed to add item to cart");
      return false;
    }
  }

  async function updateQuantity(productId, quantity) {
    try {
      await axios.put("/cart", {
        product_id: productId,
        quantity: quantity,
      });

      await loadCart();
      return true;
    } catch (err) {
      console.error("Failed to update quantity:", err);
      errorMessage.value = getErrorMessage(err, "Failed to update quantity");
      return false;
    }
  }

  async function removeFromCart(productId) {
    try {
      await axios.delete(`/cart/${productId}`);
      await loadCart();
      return true;
    } catch (err) {
      console.error("Failed to remove from cart:", err);
      errorMessage.value = getErrorMessage(err, "Failed to remove item");
      return false;
    }
  }

  async function checkout() {
    try {
      const res = await axios.post("/cart/checkout");
      resetCart();
      return {
        success: true,
        message: res.data?.message || "Checkout successful",
      };
    } catch (err) {
      console.error("Checkout failed:", err);
      const message = getErrorMessage(err, err.message || "Checkout failed");
      errorMessage.value = message;
      return {
        success: false,
        message,
      };
    }
  }

  return {
    items,
    isLoading,
    errorMessage,
    totalItems,
    totalPrice,
    loadCart,
    addToCart,
    removeFromCart,
    checkout,
    updateQuantity,
    resetCart,
    findItem,
  };
});
