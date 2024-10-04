<template>
  <div
    class="modal fade"
    id="appointmentEdit"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Consulta</h5>
          <span
            type="button"
            class="fas fa-times"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></span>
        </div>
        <div class="modal-body">
          <label style="color: black; font-size: 12px" class="ms-1"
            >Nome da pessoa responsável pelo animal</label
          >
          <input
            type="text"
            v-model="dashboardStore.appointment.client_name"
            disabled
            class="form-control mb-2"
          />

          <label
            v-if="dashboardStore.getProfile() == 'Recepcionista'"
            style="color: black; font-size: 12px"
            class="ms-1"
            >Médico</label
          >
          <select
            v-if="dashboardStore.getProfile() == 'Recepcionista'"
            v-model="dashboardStore.appointment.medic_id"
            class="form-select mb-2"
          >
            <option value="">Escolha um médico</option>
            <option :value="medic.id" v-for="medic in dashboardStore.medics">
              {{ medic.first_name }} {{ medic.last_name }}
            </option>
          </select>

          <label style="color: black; font-size: 12px" class="ms-1"
            >Animal</label
          >
          <select
            disabled
            v-model="dashboardStore.appointment.animal_id"
            class="form-select mb-2"
          >
            <option value="">Escolha um animal</option>
            <option :value="animal.id" v-for="animal in dashboardStore.animals">
              {{ animal.name }}
            </option>
          </select>

          <label style="color: black; font-size: 12px" class="ms-1"
            >Sintomas</label
          >
          <textarea
            type="text"
            v-model="dashboardStore.appointment.symptoms"
            disabled
            rows="4"
            class="form-control mb-2"
          ></textarea>

          <FullCalendar
            v-if="dashboardStore.appointment.medic_id"
            class="h-100 w-100 calendarHeight calendarWidth"
            :options="dashboardStore.calendarOptions"
          />

          <div
            class="col-12 mt-2 d-flex justify-content-between align-items-center flex-wrap"
          >
            <div>
              <label style="color: black; font-size: 12px" class="ms-1"
                >Início da consulta<span class="ms-2" style="color: #e21e26"
                  >*</span
                ></label
              >
              <VueDatePicker
                time-picker-inline
                class="mb-1"
                v-model="dashboardStore.appointment.start_appointment_date"
                :min-date="new Date()"
              />
            </div>
            <div>
              <label style="color: black; font-size: 12px" class="ms-1"
                >Fim da consulta<span class="ms-2" style="color: #e21e26"
                  >*</span
                ></label
              >
              <VueDatePicker
                time-picker-inline
                class="mb-1"
                v-model="dashboardStore.appointment.end_appointment_date"
                :min-date="new Date()"
              />
            </div>
          </div>
        </div>
        <div class="modal-footer col-12 d-flex justify-content-between">
          <button
            type="button"
            data-bs-dismiss="modal"
            class="btn btn-secondary d-flex align-items-center"
            style="height: 38px"
          >
            <span class="fas fa-times me-2"></span>
            <span>Fechar</span>
          </button>
          <button
            @click="handleCreateAppointment()"
            type="button"
            class="btn btn-success d-flex align-items-center"
            style="height: 38px"
          >
            <span class="fas fa-pencil me-2"></span>
            <span>Editar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Datepicker from "vue3-datepicker";
import { useDashboardStore } from "@/stores/dashboardStore.js";
const dashboardStore = useDashboardStore();
import FullCalendar from "@fullcalendar/vue3";
import { onMounted } from "vue";
import { toast } from "vue3-toastify";

onMounted(() => {
  if (dashboardStore.appointment.medic_id)
    dashboardStore.getAppointmentsByMedic(dashboardStore.appointment.medic_id);
});

const handleCreateAppointment = async () => {
  try {
    dashboardStore.loading = true;
    const response = await dashboardStore.editAppointment();
    if (response == "success-toast") {
      await dashboardStore.fetchData();
      const modalElement = document.getElementById("appointmentEdit");
      modalElement.classList.remove("show");
      modalElement.style.display = "none";
      document.body.classList.remove("modal-open");
      document.querySelector(".modal-backdrop").remove();
    }
    dashboardStore.loading = false;
  } catch (error) {
    dashboardStore.loading = false;
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
};
</script>
