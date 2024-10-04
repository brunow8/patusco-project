<script setup>
import { ref } from "vue";
import Datepicker from "vue3-datepicker";
import Loading from "@/components/Loading.vue";
import { useRegisterStore } from "../../stores/registerStore";
const registerStore = useRegisterStore();
const handleRegister = () => {
  registerStore.register();
};
const videoSrc = ref(
  new URL("@/assets/videos/login.mp4", import.meta.url).href
);
</script>

<template>
  <main class="main-content mt-0">
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div
            class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto"
          >
            <video autoplay loop muted class="background-video">
              <source :src="videoSrc" type="video/mp4" />
            </video>
            <Loading :isLoading="registerStore.loading" />
            <div class="d-flex justify-content-center align-items-center">
              <div
                class="col-12 p-2 p-sm-3"
                style="max-width: 500px; z-index: 1; position: fixed"
              >
                <h1
                  class="text-center"
                  style="font-weight: 600; font-family: fantasy; color: #b08c44"
                >
                  Clínica Patusco
                </h1>
                <form @submit.prevent="handleRegister">
                  <div class="col-12 card p-2">
                    <div class="col-12 d-flex justify-content-between">
                      <div class="p-2">
                        <label
                          style="color: black; font-size: 12px"
                          class="ms-1"
                          >Primeiro nome<span class="ms-2" style="color: red"
                            >*</span
                          ></label
                        >
                        <input
                          type="firstName"
                          id="firstName"
                          v-model="registerStore.user.firstName"
                          placeholder="Insira o seu primeiro nome"
                          required
                          class="form-control"
                        />
                      </div>
                      <div class="p-2">
                        <label
                          style="color: black; font-size: 12px"
                          class="ms-1"
                          >Último nome<span class="ms-2" style="color: red"
                            >*</span
                          ></label
                        >
                        <input
                          type="lastName"
                          id="lastName"
                          v-model="registerStore.user.lastName"
                          placeholder="Insira o seu último nome"
                          required
                          class="form-control"
                        />
                      </div>
                    </div>
                    <div class="ps-2 pe-2">
                      <label style="color: black; font-size: 12px" class="ms-1"
                        >Email<span class="ms-2" style="color: red"
                          >*</span
                        ></label
                      >
                      <input
                        type="email"
                        id="email"
                        v-model="registerStore.user.email"
                        placeholder="Insira o seu email"
                        required
                        class="form-control mb-2"
                        autocomplete="username"
                      />
                    </div>
                    <div class="ps-2 pe-2">
                      <label style="color: black; font-size: 12px" class="ms-1"
                        >Palavra-passe<span class="ms-2" style="color: red"
                          >*</span
                        ></label
                      >
                      <input
                        type="password"
                        id="password"
                        v-model="registerStore.user.password"
                        placeholder="Insira uma palavra-passe"
                        required
                        class="form-control mb-2"
                        autocomplete="new-password"
                      />
                    </div>
                    <div class="ps-2 pe-2">
                      <label style="color: black; font-size: 12px" class="ms-1"
                        >Confirmar palavra-passe<span
                          class="ms-2"
                          style="color: red"
                          >*</span
                        ></label
                      >
                      <input
                        type="password"
                        id="confirmPassword"
                        v-model="registerStore.user.confirmPassword"
                        placeholder="Insira a palavra-passe novamente"
                        required
                        class="form-control mb-2"
                        autocomplete="new-password"
                      />
                    </div>
                    <div class="ps-2 pe-2">
                      <label style="color: black; font-size: 12px" class="ms-1"
                        >Telemóvel</label
                      >
                      <input
                        type="phone"
                        id="cellphone"
                        v-model="registerStore.user.cellphone"
                        placeholder="Insira o número do seu telemóvel"
                        required
                        class="form-control mb-2"
                        autocomplete="phone"
                      />
                    </div>

                    <div class="ps-2 pe-2 mb-3">
                      <label style="color: black; font-size: 12px" class="ms-1"
                        >Data de nascimento<span class="ms-2" style="color: red"
                          >*</span
                        ></label
                      >
                      <Datepicker
                        @handle="teste()"
                        v-model="registerStore.user.birthday"
                        :upperLimit="
                          new Date(
                            new Date().setFullYear(
                              new Date().getFullYear() - 18
                            )
                          )
                        "
                      />
                    </div>
                  </div>
                </form>
                <div
                  class="mt-3 col-12 d-flex justify-content-center align-items-center"
                >
                  <button
                    @click="handleRegister"
                    type="submit"
                    class="button-warning col-4"
                  >
                    <span class="fas fa-check me-2"></span>Registrar
                  </button>
                </div>
                <div
                  class="mt-2 col-12 d-flex justify-content-center align-items-center"
                >
                  <a href="/login">Já tenho uma conta</a>
                </div>
              </div>
            </div>
          </div>
          <div
            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column"
          >
            <div
              class="position-relative h-100 px-7 d-flex flex-column justify-content-center overflow-hidden"
              style="
                background-image: linear-gradient(
                  310deg,
                  #e4b95e 0%,
                  #b08c44 100%
                );
                background-image: url('./../../../src/assets/images/login.webp');
                background-size: cover;
              "
            >
              <span
                class="mask opacity-6"
                style="
                  background-image: linear-gradient(
                    310deg,
                    #e4b95e 0%,
                    #b08c44 100%
                  );
                "
              ></span>
              <h4 class="mt-5 text-white font-weight-bolder position-relative">
                "Cuidando do Seu Melhor Amigo"
              </h4>
              <p class="text-white position-relative">
                Aqui, cada pata é tratada com amor, e cada coração é acolhido
                com carinho.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
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
