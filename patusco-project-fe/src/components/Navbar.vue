<script setup>
import { useLoginStore } from "@/stores/loginStore.js";
const loginStore = useLoginStore();
</script>

<template>
  <div class="col-12">
    <!-- Navbar -->
    <nav
      style="background: #b08c44; border-radius: 5px"
      class="navbar mt-3 navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
      id="navbarBlur"
      data-scroll="false"
    >
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <h6
            class="font-weight-bolder text-white mb-0 mt-1"
            style="font-weight: 900; font-size: 25px; font-family: fantasy"
          >
            Clínica Patusco - {{ loginStore.getProfile() }}
          </h6>
        </nav>
        <div class="nav-item d-lg-none ps-3 d-flex align-items-center">
          <a
            href="javascript:;"
            @click="loginStore.logout()"
            class="nav-link text-white font-weight-bold px-0"
          >
            <i class="fa fa-user me-2"></i>
            <span class="d-inline">Log out</span>
          </a>
        </div>
        <div
          class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4"
          id="navbar"
        >
          <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a
                href="javascript:;"
                @click="loginStore.logout()"
                class="nav-link text-white font-weight-bold px-0"
              >
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Log out</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
  </div>
</template>
