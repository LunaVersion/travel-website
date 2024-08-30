<template>
  <div class="listnews" id="listnews">
    <router-view></router-view>
    <HeaderComponent />
    <SidebarComponent />
  </div>
</template>

<script setup>
import SidebarComponent from '../components/SidebarComponent.vue';
import HeaderComponent from '../components/HeaderComponent.vue';

</script>

<style>

</style>