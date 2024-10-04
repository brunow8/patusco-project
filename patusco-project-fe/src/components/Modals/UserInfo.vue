<template>
  <div
    class="modal fade"
    id="userInfo"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Informação adicional
          </h5>
          <span
            type="button"
            class="fas fa-times"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></span>
        </div>
        <div class="modal-body">
          <div class="p-2">
            <label style="color: black; font-size: 12px" class="ms-1"
              >Nome</label
            >
            <input
              type="text"
              v-model="fullName"
              disabled
              class="form-control"
            />
          </div>
          <div class="p-2">
            <label style="color: black; font-size: 12px" class="ms-1"
              >Email</label
            >
            <input
              type="email"
              v-model="dashboardStore.user.email"
              disabled
              class="form-control mb-2"
            />
          </div>
          <div class="p-2">
            <label style="color: black; font-size: 12px" class="ms-1"
              >Telemóvel</label
            >
            <input
              type="phone"
              v-model="dashboardStore.user.cellphone"
              disabled
              class="form-control mb-2"
            />
          </div>
          <div class="p-2 mb-3">
            <label style="color: black; font-size: 12px" class="ms-1"
              >Data de nascimento</label
            >
            <input
              type="text"
              class="form-control mb-2"
              v-model="dashboardStore.user.birthday"
              disabled
            />
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            data-bs-dismiss="modal"
            class="btn btn-secondary d-flex align-items-center"
            style="height: 38px"
          >
            Fechar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useDashboardStore } from "@/stores/dashboardStore.js";
const dashboardStore = useDashboardStore();
const fullName = computed({
  get: () => `${dashboardStore.user.first_name} ${dashboardStore.user.last_name}`,
  set: (value) => {
    const names = value.split(" ");
    dashboardStore.user.first_name = names[0];
    dashboardStore.user.last_name = names.slice(1).join(" ");
  },
});
</script>
