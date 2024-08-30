<template>
  <!-- <div class="container"> -->
    <div class="header">
      <div class="user">
        <div class="name">
            <router-link class="name" to="/personalpage">{{ name }}</router-link> 
        </div>
        <div class="avatar"></div>
      </div>
    </div>
  <!-- </div> -->
</template>

<script setup>
const name = 'John'
</script>

<style>
.header {
  position: absolute;
  height: 100px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 0px;
  /* top: 0px;
  left: 255px; */
  border-bottom: 1px solid #A4A6B3 ;
}
.user {
  height: 50px;
  width: 80%;
  display: flex;
  align-items: center;
  justify-content: end;
}
.name {
  text-decoration: none;
  font-size: 16px;
  color: black;
  margin-right: 10px;
}
.avatar {
  height: 50px;
  width: 50px;
  background-size: 50px;
  background-image: url(../assets/avatar.jpg);
  border-radius: 50%;
}
</style>