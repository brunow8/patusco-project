import { defineStore } from "pinia";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
export const useRegisterStore = defineStore("register", {
  state: () => ({
    user: {
      email: "",
      password: "",
      firstName: "",
      lastName: "",
      confirmPassword: "",
      cellphone: "",
      birthday: new Date(new Date().setFullYear(new Date().getFullYear() - 18)),
    },
    loading: false,
  }),
  actions: {
    async register() {
      try {
        this.loading = true;
        await axios
          .post("/register", {
            newUser: this.user,
          })
          .then((res) => {
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
            if (res.data.errorType == "success-toast")
              setTimeout(() => {
                window.location.href = "/login";
              }, 1000);
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
  },
});
