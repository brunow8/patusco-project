<template>
  <div class="col-12">
    <div class="row d-flex flex-column-reverse flex-md-row m-4">
      <div
        class="col-12 ps-0 d-flex justify-content-start"
        v-if="dashboardStore.getProfile() == 'Recepcionista'"
      >
        <button
          @click="dashboardStore.openCreateAppointmentModal()"
          class="btn btn-success d-flex justify-content-center align-items-center"
        >
          <span class="fas fa-plus me-2"></span><span>Criar consulta</span>
        </button>
      </div>
      <div class="col-md-9 col-12 p-2 card" style="border-radius: 5px">
        <div class="col-12 p-2">
          <EasyDataTable
            :headers="headers"
            :items="dashboardStore.appointments"
          >
            <template #item-client_name="item">
              <div
                class="d-flex justify-content-start align-items-center"
                @click="dashboardStore.openUserInfoModal(item.client_id)"
              >
                <span
                  class="fas fa-user me-2"
                  style="color: rgb(25, 160, 218)"
                ></span>
                <span>{{ item.client_name }}</span>
              </div>
            </template>
            <template #item-medic_name="item">
              <div
                v-if="item.medic_id != null"
                class="d-flex justify-content-start align-items-center"
                @click="dashboardStore.openUserInfoModal(item.medic_id)"
              >
                <span
                  class="fas fa-user-doctor me-2"
                  style="color: orange"
                ></span>
                <span>{{ item.medic_name }}</span>
              </div>
            </template>
            <template #item-animal_name="item">
              <div
                class="d-flex justify-content-start align-items-center"
                @click="dashboardStore.openAnimalInfoModal(item.animal_id)"
              >
                <span class="fas fa-paw me-2" style="color: brown"></span>
                <span>{{ item.animal_name }}</span>
              </div>
            </template>
            <template #item-symptoms="item">
              <div
                style="
                  max-width: 250px !important;
                  word-break: break-all;
                  overflow: hidden;
                  white-space: nowrap;
                "
                :title="item.symptoms"
                class="d-flex justify-content-start align-items-center"
              >
                {{ item.symptoms }}
              </div>
            </template>
            <template #item-actions="item">
              <div class="d-flex justify-content-between align-items-center">
                <div @click="dashboardStore.openAppointmentModal(item)">
                  <span
                    class="fas fa-pencil"
                    style="color: #00a5ff; cursor: pointer"
                  ></span>
                </div>
                <div
                  @click="dashboardStore.deleteAppointment(item.appointment_id)"
                  v-if="dashboardStore.getProfile() == 'Recepcionista'"
                >
                  <span
                    class="fas fa-trash"
                    style="color: #e21e26; cursor: pointer"
                  ></span>
                </div>
              </div>
            </template>
          </EasyDataTable>
          <UserInfo />
          <AnimalInfo />
          <AppointmentEdit />
          <AppointmentCreate />
        </div>
      </div>
      <div class="col-md-3 col-12 pe-0 ps-0 ps-md-3 mb-3 mb-md-0">
        <div class="col-12 card p-3" style="border-radius: 5px">
          <label class="ms-0 mb-0">Data de início</label>
          <VueDatePicker
            time-picker-inline
            class="mb-1"
            v-model="dashboardStore.filters.fromDate"
          />

          <label class="ms-0 mb-0">Data de início</label>
          <VueDatePicker
            time-picker-inline
            class="mb-1"
            v-model="dashboardStore.filters.toDate"
          />

          <label class="ms-0 mb-0">Tipos de animal</label>
          <select
            v-model="dashboardStore.filters.animal_type"
            class="form-select mb-1"
          >
            <option value="">Escolha um tipo</option>
            <option v-for="type in dashboardStore.animalTypes" :value="type.id">
              {{ type.name }}
            </option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onBeforeMount } from "vue";
import UserInfo from "../../components/Modals/UserInfo.vue";
import AnimalInfo from "../../components/Modals/AnimalInfo.vue";
import AppointmentEdit from "../../components/Modals/AppointmentEdit.vue";
import { useDashboardStore } from "@/stores/dashboardStore.js";
import Datepicker from "vue3-datepicker";
import AppointmentCreate from "../../components/Modals/AppointmentCreate.vue";
const dashboardStore = useDashboardStore();
const headers = ref([
  { text: "CLIENTE", value: "client_name" },
  { text: "MÉDICO", value: "medic_name" },
  { text: "ANIMAL", value: "animal_name" },
  { text: "SINTOMAS", value: "symptoms" },
  { text: "DATA DA CONSULTA", value: "start_appointment_date" },
  { text: "AÇÕES", value: "actions" },
]);

watch(
  () => [
    dashboardStore.filters.fromDate,
    dashboardStore.filters.toDate,
    dashboardStore.filters.animal_type,
  ],
  async () => {
    dashboardStore.loading = true;
    try {
      await dashboardStore.fetchData();
      dashboardStore.loading = false;
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  }
);

onBeforeMount(async () => {
  dashboardStore.loading = true;
  await dashboardStore.fetchData();
  await dashboardStore.getAnimalTypes();
  if (dashboardStore.getProfile() == "Recepcionista") {
    await dashboardStore.getClients();
    await dashboardStore.getMedics();
  }
  await dashboardStore.getAnimals();
  dashboardStore.loading = false;
});
</script>

<style>
a {
  color: black !important;
}
</style>
