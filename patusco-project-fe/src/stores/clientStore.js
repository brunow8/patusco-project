import { defineStore } from "pinia";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
export const useClientStore = defineStore("client", {
  state: () => ({
    user: {
      birthday: "",
      name: "",
      email: "",
      cellphone: "",
    },
    appointment: {
      personName: "",
      email: "",
      cellphone: "",
      petName: "",
      petType: "",
      petBreed: "",
      birthdayPet: new Date(),
      symptoms: "",
      appointmentDate: new Date(),
    },
    animalTypes: [],
    loading: false,
  }),
  actions: {
    async getUserDetail() {
      try {
        await axios.get("/user-detail").then((res) => {
          toast(res.data.message, {
            autoClose: 2000,
            position: toast.POSITION.BOTTOM_CENTER,
            toastClassName: res.data.errorType,
          });
          if (res.data.errorType == "success-toast") {
            this.appointment.personName = res.data.data.name;
            this.appointment.email = res.data.data.email;
            this.appointment.cellphone = res.data.data.cellphone;
          }
        });
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
    async getAnimalTypes() {
      try {
        await axios.get("/animal-types").then((res) => {
          if (res.data.errorType != "success-toast")
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
          else {
            this.animalTypes = res.data.data;
          }
        });
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
    async createAppointment() {
      try {
        this.loading = true;
        let res = await axios.post("/client-appointment", {
          appointment: {
            petName: this.appointment.petName,
            petBreed: this.appointment.petBreed,
            petType: this.appointment.petType,
            birthdayPet: this.appointment.birthdayPet,
            symptoms: this.appointment.symptoms,
            appointmentDate: this.appointment.appointmentDate,
          },
        });
        toast(res.data.message, {
          autoClose: 2000,
          position: toast.POSITION.BOTTOM_CENTER,
          toastClassName: res.data.errorType,
        });
        if (res.data.errorType == "success-toast")
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        this.loading = false;
      } catch (error) {
        console.log(error);
        this.loading = false;
        toast(
          "Um erro inesperado aconteceu. Por favor contacte o suporte ou tente novamente!",
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
