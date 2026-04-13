<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="page-hero">
        <div>
          <span class="pill">Admin</span>
          <h1 class="section-heading__title mt-3">Manage categories</h1>
          <p class="section-heading__copy mb-0">
            Add new catalogue categories and remove unused ones.
          </p>
        </div>
      </div>

      <div class="toolbar-panel">
        <div class="category-form">
          <input
            v-model="newCategoryName"
            type="text"
            class="form-control"
            placeholder="New category name"
          />
          <button class="btn btn-primary" @click="createCategory">Add category</button>
        </div>
      </div>

      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <div v-if="success" class="alert alert-success">{{ success }}</div>

      <div class="data-panel">
        <div v-if="categories.length === 0" class="empty-panel">
          <h2>No categories found.</h2>
          <p class="mb-0">Create a category to start organizing products.</p>
        </div>

        <div v-else class="data-table">
          <div class="data-table__head data-table__head--two">
            <span>Category</span>
            <span>Action</span>
          </div>

          <div v-for="cat in categories" :key="cat.id" class="data-table__row data-table__row--two">
            <span class="data-table__value">{{ cat.name }}</span>
            <span>
              <button class="btn btn-danger btn-sm" @click="deleteCategory(cat.id)">Delete</button>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from "vue";
import axios from "../../axios-auth";
import { useAuthStore } from "../../stores/auth";
import { useRouter } from "vue-router";

const auth = useAuthStore();
const router = useRouter();

const categories = ref([]);
const newCategoryName = ref("");
const error = ref(null);
const success = ref(null);

async function loadCategories() {
  try {
    const res = await axios.get("/categories");
    categories.value = res.data;
  } catch (err) {
    error.value = "Failed to load categories.";
  }
}

async function createCategory() {
  error.value = null;
  success.value = null;
  if (!newCategoryName.value.trim()) {
    error.value = "Category name cannot be empty.";
    return;
  }

  try {
    const res = await axios.post("/categories", { name: newCategoryName.value.trim() });
    categories.value.push(res.data);
    newCategoryName.value = "";
    success.value = "Category added successfully!";
  } catch (err) {
    error.value = err.response?.data?.errorMessage || "Failed to add category";
  }
}

async function deleteCategory(id) {
  error.value = null;
  success.value = null;

  if (!confirm("Are you sure you want to delete this category?")) return;

  try {
    await axios.delete(`/categories/${id}`);
    categories.value = categories.value.filter((cat) => cat.id !== id);
    success.value = "Category deleted successfully!";
  } catch (err) {
    error.value = err.response?.data?.errorMessage || "Failed to delete category. Category is in use.";
  }
}

onMounted(() => {
  if (auth.role !== "admin") {
    router.push("/");
  } else {
    loadCategories();
  }
});
</script>
