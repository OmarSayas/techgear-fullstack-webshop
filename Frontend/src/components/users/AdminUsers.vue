<script setup>
import { onMounted, ref } from "vue";
import axios from "../../axios-auth";

const users = ref([]);
const isLoading = ref(false);
const errorMessage = ref("");

async function fetchUsers() {
  isLoading.value = true;
  try {
    const res = await axios.get("/admin/users");
    users.value = res.data.map((user) => ({
      ...user,
      roleLabel: user.role === 2 ? "admin" : "user",
      selectedRole: user.role === 2 ? "admin" : "user",
    }));
    errorMessage.value = "";
  } catch (err) {
    errorMessage.value = "Failed to load users.";
    console.error(err);
  } finally {
    isLoading.value = false;
  }
}

async function updateRole(userId, newRole) {
  const roleNumber = newRole === "admin" ? 2 : 1;
  try {
    await axios.put(`/admin/users/${userId}`, { role: roleNumber });
    await fetchUsers();
  } catch (err) {
    errorMessage.value = "Failed to update role.";
    console.error(err);
  }
}

onMounted(fetchUsers);
</script>

<template>
  <section class="section-shell section-shell--compact">
    <div class="container">
      <div class="page-hero">
        <div>
          <span class="pill">Admin</span>
          <h1 class="section-heading__title mt-3">Manage users</h1>
          <p class="section-heading__copy mb-0">
            Review registered accounts and update their access level.
          </p>
        </div>
      </div>

      <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>

      <div v-if="isLoading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div v-else class="data-panel">
        <div class="data-table">
          <div class="data-table__head">
            <span>Username</span>
            <span>Email</span>
            <span>Role</span>
            <span>Change role</span>
          </div>

          <div v-for="user in users" :key="user.id" class="data-table__row">
            <span class="data-table__value">{{ user.username }}</span>
            <span class="data-table__value data-table__value--muted">{{ user.email }}</span>
            <span>
              <span :class="['role-chip', user.roleLabel === 'admin' ? 'role-chip--admin' : 'role-chip--user']">
                {{ user.roleLabel }}
              </span>
            </span>
            <span>
              <select
                class="form-select data-table__select"
                v-model="user.selectedRole"
                @change="updateRole(user.id, user.selectedRole)"
              >
                <option value="user">user</option>
                <option value="admin">admin</option>
              </select>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
