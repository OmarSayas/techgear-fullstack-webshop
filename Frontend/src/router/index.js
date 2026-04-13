import { createRouter, createWebHistory } from "vue-router";

import Home from "../components/Home.vue";
import ProductList from "../components/products/ProductList.vue";
import CreateProduct from "../components/products/CreateProduct.vue";
import EditProduct from "../components/products/EditProduct.vue";
import Login from "../components/users/Login.vue";
import CartView from "../components/users/CartView.vue";
import ProductDetail from "../components/products/ProductDetail.vue";
import UserOrders from "../components/users/UserOrders.vue";
import AdminUsers from "../components/users/AdminUsers.vue";
import CategoryManager from "../components/products/CategoryManager.vue";
import NotFound from "../components/NotFound.vue";
import { useAuthStore } from "../stores/auth";

const routes = [
  { path: "/", component: Home, meta: { title: "Home" } },
  { path: "/products", component: ProductList, meta: { title: "Products" } },
  {
    path: "/product/:id",
    component: ProductDetail,
    props: true,
    meta: { title: "Product details" },
  },
  {
    path: "/createproduct",
    component: CreateProduct,
    meta: { requiresAuth: true, role: "admin", title: "Create product" },
  },
  {
    path: "/editproduct/:id",
    component: EditProduct,
    props: true,
    meta: { requiresAuth: true, role: "admin", title: "Edit product" },
  },
  {
    path: "/admin/users",
    component: AdminUsers,
    meta: { requiresAuth: true, role: "admin", title: "Manage users" },
  },
  {
    path: "/admin/categories",
    component: CategoryManager,
    meta: { requiresAuth: true, role: "admin", title: "Manage categories" },
  },
  {
    path: "/cart",
    component: CartView,
    meta: { requiresAuth: true, role: "user", title: "Shopping cart" },
  },
  {
    path: "/orders",
    component: UserOrders,
    meta: { requiresAuth: true, role: "user", title: "My orders" },
  },
  { path: "/login", component: Login, meta: { title: "Login" } },
  {
    path: "/:pathMatch(.*)*",
    component: NotFound,
    meta: { title: "Page not found" },
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore();

  if (to.meta.requiresAuth && !auth.isLoggedIn) {
    next({ path: "/login", query: { redirect: to.fullPath } });
  } else if (to.meta.role && auth.role !== to.meta.role) {
    next("/products");
  } else {
    next();
  }
});

router.afterEach((to) => {
  document.title = to.meta?.title
    ? `${to.meta.title} | TechGear`
    : "TechGear | Full-Stack PC Webshop";
});

export default router;
