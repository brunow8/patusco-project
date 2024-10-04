<template>
  <div v-if="isLoading" class="d-flex justify-content-center">
    <div class="modal-loading">
      <div class="modal-content-loading">
        <img src="@/assets/gifs/loading.gif" alt="" loop style="border-radius: 50%;" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  isLoading: {
    type: Boolean,
    required: true,
  },
});
</script>

<style>
/* Styling for the modal container and content */
.modal-loading {
  display: flex;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 2;
}

.modal-content-loading {
  border-radius: 5px;
  background-color: var(--bg-color);
  position: absolute;
  top: 50%;
  width: 90px;
  left: 50%;
  transform: translate(-50%, -50%);
  padding: 20px;
  border: none !important;
  display: flex;
  flex-direction: column;
  pointer-events: auto;
  outline: 0;
}
</style>
