<template>
  <div class="container">
    <div class="sidebar">
      <h1 class="sidebar_text">Admin</h1>
      <div class="sidebar_desktop">
        <div class="item">
          <i class='bx bx-chart'></i>
          <router-link class="router-link" to="/tours">Tours</router-link> <br>
        </div>
        <div class="item">   
          <i class='bx bx-chart'></i>    
          <router-link class="router-link" to="/listnews">News</router-link> <br>
        </div>
        <div class="item">  
          <i class='bx bx-chart'></i>      
          <router-link class="router-link" to="/bookings">Bookings</router-link> <br>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

</script>

<style>
.sidebar {
  position: absolute;
  height: 100%;
  width: 255px;
  background-color: #363740;
  left: 0px;
  top: 0px;
}
.sidebar_text {
  position: absolute;
  color: #fff;
  font-weight: 700;
  font-size: 45px;
  top: 39px;
  left: 55px;
}
.sidebar_desktop  {
  position: absolute;
  top: 130px;
  left: 30px;
}
.item {
margin: 20px;
}
.bx {
  color: #A4A6B3;
  margin-right: 15px;
}
.router-link {
  text-decoration: none;
  color: #A4A6B3;
}
</style>