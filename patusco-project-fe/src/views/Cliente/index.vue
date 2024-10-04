<script setup>
import Loading from "@/components/Loading.vue";
import { onBeforeMount } from "vue";
import Datepicker from "vue3-datepicker";
import { toast } from "vue3-toastify";
import { useClientStore } from "@/stores/clientStore.js";
const clientStore = useClientStore();

onBeforeMount(async () => {
  try {
    clientStore.loading = true;
    await clientStore.getUserDetail();
    await clientStore.getAnimalTypes();
    clientStore.loading = false;
  } catch (error) {
    console.log(error);
    clientStore.loading = false;
    toast(
      "Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!",
      {
        autoClose: 2000,
        position: toast.POSITION.BOTTOM_CENTER,
        toastClassName: "error-toast",
      }
    );
  }
});
</script>

<template>
  <div class="col-12 h-100">
    <Loading :isLoading="clientStore.loading" />
    <div class="row m-4">
      <div class="col-12 p-2 card" style="border-radius: 5px">
        <form @submit.prevent="clientStore.createAppointment()">
          <h5
            class="text-center mt-2"
            style="
              font-weight: 900;
              font-family: fantasy;
              color: #b08c44;
              font-size: 30px;
            "
          >
            Marcação de consulta
          </h5>
          <label style="color: black; font-size: 12px" class="ms-1"
            >Nome da pessoa responsável pelo animal</label
          >
          <input
            type="name"
            id="personName"
            v-model="clientStore.appointment.personName"
            disabled
            class="form-control"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Email</label
          >
          <input
            type="email"
            id="email"
            v-model="clientStore.appointment.email"
            placeholder="Insira o seu email"
            disabled
            class="form-control mb-2"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Telemóvel</label
          >
          <input
            type="cellphone"
            id="cellphone"
            v-model="clientStore.appointment.cellphone"
            placeholder="Insira o seu telemóvel"
            disabled
            class="form-control mb-2"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Nome do animal<span class="ms-2" style="color: red">*</span></label
          >
          <input
            type="name"
            id="petName"
            v-model="clientStore.appointment.petName"
            placeholder="Insira o nome do seu animal"
            required
            class="form-control mb-2"
            autocomplete="name"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Tipo de animal<span class="ms-2" style="color: red">*</span></label
          >
          <select
            id="petType"
            v-model="clientStore.appointment.petType"
            required
            class="form-select mb-2"
          >
            <option value="">Escolha um tipo</option>
            <option v-for="type in clientStore.animalTypes" :value="type.id">
              {{ type.name }}
            </option>
          </select>
          <label style="color: black; font-size: 12px" class="ms-1"
            >Raça do animal</label
          >
          <input
            type="name"
            id="petBreed"
            v-model="clientStore.appointment.petBreed"
            placeholder="Indique o raça do seu animal"
            required
            class="form-control mb-2"
            autocomplete="name"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Data de nascimento do animal<span class="ms-2" style="color: red"
              >*</span
            ></label
          >
          <Datepicker
            class="mb-1"
            v-model="clientStore.appointment.birthdayPet"
            :upperLimit="new Date()"
          />
          <label style="color: black; font-size: 12px" class="ms-1"
            >Sintomas<span class="ms-2" style="color: red">*</span></label
          >
          <textarea
            type="name"
            id="symptoms"
            v-model="clientStore.appointment.symptoms"
            placeholder="Insira os sintomas do seu animal"
            required
            class="form-control mb-2"
            autocomplete="name"
            rows="4"
          >
          </textarea>
          <label style="color: black; font-size: 12px" class="ms-1"
            >Data desejada da consulta<span class="ms-2" style="color: red"
              >*</span
            ></label
          >
          <VueDatePicker
            time-picker-inline
            class="mb-1"
            v-model="clientStore.appointment.appointmentDate"
            :min-date="new Date()"
          />
          <div
            class="col-12 mt-2 d-flex justify-content-end align-items-center"
          >
            <button
              class="mb-0 btn btn-success d-flex justify-content-center align-items-center"
            >
              <span class="fas fa-check"></span>
              <span class="ms-2">Agendar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style>
.v3dp__input_wrapper > input {
  height: 38px;
  color: black;
  font-size: 14px;
  background-color: transparent !important;
}
.vue-tel-input > input {
  height: 38px;
  color: black;
  font-size: 14px;
  background-color: transparent !important;
}
</style>
