<script setup>
import { defineProps, computed } from "vue";
import Navbar from "./components/Navbar.vue";
import { useRoute } from "vue-router";
const route = useRoute();
const isAuthRoute = computed(() => {
  return route.name === "Login" || route.name === "Register";
});
defineProps();
</script>

<template>
  <Navbar v-if="!isAuthRoute" />
  <router-view />
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
