import { defineStore } from "pinia";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
export const useLoginStore = defineStore("login", {
  state: () => ({
    user: {
      email: "",
      password: "",
    },
    loading: false,
  }),
  actions: {
    async login() {
      try {
        this.loading = true;
        await axios
          .post("/login", {
            user: { email: this.user.email, password: this.user.password },
          })
          .then((res) => {
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
            if (res.data.errorType == "success-toast") {
              const token = res.data.token;
              localStorage.setItem("auth_token", token);
              if (res.data.profile == 1) {
                localStorage.setItem("profile", "Admnistrador");
                window.location.href = "/admin";
              }
              if (res.data.profile == 2) {
                localStorage.setItem("profile", "Médico");
                window.location.href = "/";
              }
              if (res.data.profile == 3) {
                localStorage.setItem("profile", "Utente");
                window.location.href = "/utente";
              }
              if (res.data.profile == 4) {
                localStorage.setItem("profile", "Recepcionista");
                window.location.href = "/";
              }
            }
          });
        this.loading = false;
      } catch (error) {
        console.log(error);
        this.loading = false;
        toast(
          "Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!",
          {
            autoClose: 2000,
            position: toast.POSITION.BOTTOM_CENTER,
            toastClassName: "error-toast",
          }
        );
      }
    },
    logout() {
      try {
        localStorage.removeItem("auth_token");
        window.location.href = "/login";
      } catch (error) {
        console.log(error);
        toast(
          "Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!",
          {
            autoClose: 2000,
            position: toast.POSITION.BOTTOM_CENTER,
            toastClassName: "error-toast",
          }
        );
      }
    },
    getProfile() {
      return localStorage.getItem("profile");
    },
  },
});
