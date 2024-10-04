<template>
  <div
    class="modal fade"
    id="appointmentCreate"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl">
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
            >Utente<span class="ms-2" style="color: #e21e26">*</span></label
          >
          <select
            class="form-select"
            v-model="dashboardStore.appointment.client_id"
          >
            <option value="">Escolha um utente</option>
            <option
              :value="client.id"
              v-for="client in dashboardStore.clients"
              :key="client.id"
            >
              {{ client.first_name }} {{ client.last_name }}
            </option>
          </select>

          <label
            v-if="!dashboardStore.isMedic"
            style="color: black; font-size: 12px"
            class="ms-1"
            >Nome do médico<span class="ms-2" style="color: #e21e26"
              >*</span
            ></label
          >
          <select
            class="form-select"
            v-model="dashboardStore.appointment.medic_id"
          >
            <option value="">Escolha um médico</option>
            <option
              :value="medic.id"
              v-for="medic in dashboardStore.medics"
              :key="medic.id"
            >
              {{ medic.first_name }} {{ medic.last_name }}
            </option>
          </select>

          <label style="color: black; font-size: 12px" class="ms-1"
            >Nome do animal<span class="ms-2" style="color: #e21e26"
              >*</span
            ></label
          >
          <select
            class="form-select"
            v-model="dashboardStore.appointment.animal_id"
          >
            <option value="">Escolha um animal</option>
            <option
              :value="animal.id"
              v-for="animal in dashboardStore.animalsByClient"
              :key="animal.id"
            >
              {{ animal.name }}
            </option>
          </select>

          <label style="color: black; font-size: 12px" class="ms-1"
            >Sintomas<span class="ms-2" style="color: #e21e26">*</span></label
          >
          <textarea
            type="text"
            v-model="dashboardStore.appointment.symptoms"
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
            <span class="fas fa-plus me-2"></span>
            <span>Criar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { watch, ref } from "vue";
import { useDashboardStore } from "@/stores/dashboardStore.js";
import FullCalendar from "@fullcalendar/vue3";
import { toast } from "vue3-toastify";

const dashboardStore = useDashboardStore();
watch(
  () => dashboardStore.appointment.client_id,
  (clientId) => {
    dashboardStore.getAnimalsByClientId(clientId);
  }
);
watch(
  () => dashboardStore.appointment.medic_id,
  (medicId) => {
    dashboardStore.getAppointmentsByMedic(medicId);
  }
);

const handleCreateAppointment = async () => {
  try {
    dashboardStore.loading = true;
    const response = await dashboardStore.createAppointment();
    dashboardStore.loading = false;
    if (response == "success-toast") {
      const modalElement = document.getElementById("appointmentCreate");
      modalElement.classList.remove("show");
      modalElement.style.display = "none";
      document.body.classList.remove("modal-open");
      document.querySelector(".modal-backdrop").remove();
    }
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
<style>
/* @media (min-width: 1500px){ */
.calendarWidth {
}
/* } */
.calendarHeight {
}
@media (min-height: 754.98px) {
  .calendarHeight {
  }
}
@media (min-height: 942.98px) {
  .calendarHeight {
  }
}
@media (min-height: 1005.98px) {
  .calendarHeight {
  }
}
.fc-toolbar-title,
.fc-col-header-cell-cushion,
.fc-daygrid-day-number {
  color: var(--text-color) !important;
}
.fc .fc-button-primary:disabled {
  background: none;
  border-radius: 8px;
  border: 1px solid var(--bg-blue-nav);
  cursor: pointer;
  color: var(--text-color);
  opacity: 0.5;
}
.fc .fc-button-primary {
  background: none;
  border-radius: 8px;
  border: 1px solid var(--bg-blue-nav);
  cursor: pointer;
  color: var(--text-color);
}
.fc .fc-button-primary:hover {
  background: var(--bg-blue-nav);
  color: white;
  border-color: var(--bg-blue-nav);
}
.fc-scrollgrid {
  border-radius: 8px;
}
.fc .fc-daygrid-day.fc-day-today {
  background-color: rgb(255 0 0 / 22%);
}
.fc .fc-daygrid-event {
  background: var(--bg-blue-nav);
  background-color: var(--bg-blue-nav);
  border-color: var(--bg-blue-nav);
  cursor: pointer;
}
.fc-daygrid-event-dot {
  border-color: #e21e26;
}
.fc-daygrid-event-harness {
  background: orange;
  border-radius: 5px;
  margin: 5px;
}
</style>
