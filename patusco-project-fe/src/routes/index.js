// router/index.js
import { createRouter, createWebHistory } from "vue-router";
import Login from "@/views/Login/index.vue";
import Register from "@/views/Register/index.vue";
import axios from "axios";

const routes = [
  {
    path: "/",
    name: "Dashboard",
    component: () => import("@/views/Dashboard/index.vue"),
    meta: { requiresAuth: true, profiles: [2, 4] },
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/registro",
    name: "Register",
    component: Register,
  },
  {
    path: "/utente",
    name: "Cliente",
    component: () => import("@/views/Cliente/index.vue"),
    meta: { requiresAuth: true, profiles: [3] },
  },
  // {
  //   path: "/admin",
  //   name: "Admin",
  //   component: () => import("@/views/Admin/index.vue"),
  //   meta: { requiresAuth: true, profiles: [1] },
  // },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
router.beforeEach((to, from, next) => {
  const authToken = localStorage.getItem("auth_token");

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!authToken) {
      next({ path: "/login" });
    } else {
      const tokenPayload = JSON.parse(atob(authToken.split(".")[1]));
      if (
        to.meta.profiles &&
        to.meta.profiles.includes(tokenPayload.profile_id)
      ) {
        next();
      } else {
        if (tokenPayload.profile_id == 1) next({ path: "/admin" });
        if (tokenPayload.profile_id == 2) next({ path: "/" });
        if (tokenPayload.profile_id == 3) next({ path: "/utente" });
        if (tokenPayload.profile_id == 4) next({ path: "/" });
      }
    }
  } else {
    if (authToken && (to.path === "/login" || to.path === "/registro")) {
      next({ path: "/" });
    } else {
      next();
    }
  }
});

async function fetchCsrfToken() {
  try {
    const response = await axios.get("http://localhost:8000/api/csrf-token", {
      withCredentials: true,
    });
    axios.defaults.headers.common["X-CSRF-TOKEN"] = response.data.csrf_token;
  } catch (error) {
    console.error("Failed to fetch CSRF token:", error);
  }
}

fetchCsrfToken();
export default router;
