import { defineStore } from "pinia";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import dayGridPlugin from "@fullcalendar/daygrid";
export const useDashboardStore = defineStore("dashboard", {
  state: () => ({
    clients: [],
    medics: [],
    animals: [],
    appointment: {
      client_id: "",
      medic_id: "",
      animal_id: "",
      symptoms: "",
      start_appointment_date: "",
      end_appointment_date: "",
    },
    profile: "",
    animalTypes: [],
    filters: {
      search: "",
      fromDate: null,
      toDate: null,
      animal_type: "",
    },
    animal: {
      name: "",
      breed: "",
      animal_type: "",
      birthday: "",
    },
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
    appointments: [],
    animalsByClient: [],
    calendarOptions: {
      plugins: [dayGridPlugin],
      initialView: "dayGridMonth",
      weekends: true,
      events: [],
      eventContent: (arg) => {
        const startDate = arg.event.start.toLocaleString();
        const endDate = arg.event.end.toLocaleString();
        return {
          html: ` 
                 Início: ${startDate.match(/\d{2}:\d{2}:\d{2}/)[0]}<br/> 
                 Fim: ${endDate.match(/\d{2}:\d{2}:\d{2}/)[0]}`,
        };
      },
    },
  }),
  actions: {
    async fetchData() {
      try {
        const response = await axios.post("/appointments", {
          filters: this.filters,
        });
        this.appointments = response.data.data;
      } catch (error) {
        console.log(error);
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

    async getAnimalTypes() {
      try {
        const response = await axios.get("/animal-types");
        this.animalTypes = response.data.data;
      } catch (error) {
        console.log(error);
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

    async deleteAppointment(appointmentId) {
      try {
        this.loading = true;
        await axios.delete("/appointment/" + appointmentId);
        await this.fetchData();
        this.loading = false;
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
    async openUserInfoModal(userId) {
      try {
        this.loading = true;
        const response = await axios.get("/user-detail/" + userId);
        this.user = response.data.data;
        this.loading = false;
        const modalElement = document.getElementById("userInfo");
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
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
    async openAnimalInfoModal(animalId) {
      try {
        this.loading = true;
        const response = await axios.get("/animal-detail/" + animalId);
        this.animal = response.data.data;
        this.loading = false;
        const modalElement = document.getElementById("animalInfo");
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
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

    async openAppointmentModal(item) {
      try {
        this.appointment = item;
        this.appointment.start_appointment_date = new Date(
          this.appointment.start_appointment_date
        );
        const modalElement = document.getElementById("appointmentEdit");
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
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
    openCreateAppointmentModal() {
      try {
        const modalElement = document.getElementById("appointmentCreate");
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
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
    async getClients() {
      try {
        await axios.get("/clients").then((res) => {
          this.clients = res.data.data;
        });
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
    async getMedics() {
      try {
        await axios.get("/medics").then((res) => {
          this.medics = res.data.data;
        });
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

    async getAnimals() {
      try {
        await axios.get("/animals").then((res) => {
          this.animals = res.data.data;
        });
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
    async editAppointment() {
      try {
        let res = null;
        if (this.getProfile() == "Médico") {
          res = await axios.put("/appointment-medic", {
            appointment: this.appointment,
          });
        } else {
          res = await axios.put("/appointment-recepcionist", {
            appointment: this.appointment,
          });
        }
        toast(res.data.message, {
          autoClose: 2000,
          position: toast.POSITION.BOTTOM_CENTER,
          toastClassName: res.data.errorType,
        });
        return res.data.errorType;
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
    getAnimalsByClientId(clientId) {
      try {
        axios.get("/animals-by-client/" + clientId).then((res) => {
          if (res.data.errorType != "success-toast") {
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
          } else {
            this.animalsByClient = res.data.data;
          }
        });
        this.loading = false;
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
    getAppointmentsByMedic(medicId) {
      try {
        axios.get("/appointments-by-medic/" + medicId).then((res) => {
          if (res.data.errorType != "success-toast") {
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
          } else {
            this.calendarOptions.events = res.data.data;
          }
        });
        this.loading = false;
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
    async createAppointment() {
      try {
        this.loading = true;
        await axios
          .post("/recepcionist-appointment/", { appointment: this.appointment })
          .then(async (res) => {
            toast(res.data.message, {
              autoClose: 2000,
              position: toast.POSITION.BOTTOM_CENTER,
              toastClassName: res.data.errorType,
            });
            await this.getAppointmentsByMedic(this.appointment.medic_id);
            this.loading = false;
            return res.data.errorType;
          });
      } catch (error) {
        this.loading = false;
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
  },
});
