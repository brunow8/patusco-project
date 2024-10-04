<script setup>
import { ref } from "vue";
import { useLoginStore } from "@/stores/loginStore.js";
import Loading from "@/components/Loading.vue";

const loginStore = useLoginStore();

const handleLogin = () => {
  loginStore.login();
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
            <Loading :isLoading="loginStore.loading" />
            <video autoplay loop muted class="background-video">
              <source :src="videoSrc" type="video/mp4" />
            </video>

            <div class="d-flex justify-content-center align-items-center">
              <div
                class="col-12 p-2 p-sm-3"
                style="max-width: 400px; z-index: 1; position: fixed"
              >
                <h1
                  class="text-center"
                  style="font-weight: 600; font-family: fantasy; color: #b08c44"
                >
                  Clínica Patusco
                </h1>
                <form @submit.prevent="handleLogin">
                  <input
                    type="email"
                    id="email"
                    v-model="loginStore.user.email"
                    placeholder="Insira o seu email"
                    required
                    class="form-control mb-2"
                    autocomplete="username"
                  />
                  <input
                    type="password"
                    id="password"
                    v-model="loginStore.user.password"
                    placeholder="Insira a sua palavra-passe"
                    required
                    class="form-control mb-2"
                    autocomplete="new-password"
                  />
                  <div
                    class="col-12 d-flex justify-content-end align-items-center"
                  >
                    <button type="submit" class="button-warning col-4">
                      <span class="fas fa-arrow-right me-2"></span>Login
                    </button>
                  </div>

                  <div
                    class="mt-3 col-12 d-flex justify-content-center align-items-center"
                  >
                    <a href="/registro">Registrar nova conta</a>
                  </div>
                </form>
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
