import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";
import router from "./routes/index";
import VueAxios from "vue-axios";
import axios from "axios";
import "./assets/css/style.css";
import "./assets/css/argon-dashboard.css";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import Cookies from "js-cookie";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
const app = createApp(App);
const pinia = createPinia();
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

window.axios = axios;
window.axios.defaults.baseURL = "http://localhost:8000/api";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Content-Type"] = "application/json";

window.axios.interceptors.request.use(
  (config) => {
    let csrfToken = Cookies.get("XSRF-TOKEN");
    if (csrfToken)
      config.headers["X-XSRF-TOKEN"] = csrfToken.endsWith("=")
        ? csrfToken.slice(0, -1)
        : csrfToken;
    const token = localStorage.getItem("auth_token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

window.axios.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    const { response } = error;
    if (response && response.status === 401) {
      localStorage.removeItem("auth_token");
      router.push({ name: "Login" });
    }
    return Promise.reject(error);
  }
);

app.use(pinia);
app.use(router);
app.use(VueAxios, axios);
app.component("EasyDataTable", Vue3EasyDataTable);
app.component("VueDatePicker", VueDatePicker);
app.provide("axios", app.config.globalProperties.axios);
app.mount("#app");
