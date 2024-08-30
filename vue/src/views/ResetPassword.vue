<template>
  <div class="container">
        <div class="row">
            <div class="col-6 left_screen">
                <div>
                <div class="text">
                    <h1>Reset Password</h1>
                </div>
                <form action="" @submit.prevent="submit">
                    <div class="input">
                        <label class="label" for="password">New Password</label> <br>
                        <input type="text" id="password" placeholder="Enter your new password" v-model="newpassword"> <br>
                        <i class='bx bx-low-vision'></i>
                    </div>
                    <div class="input">
                        <label class="label" for="confirmpassword">Confirm Password</label> <br>
                        <input type="text" id="confirmpassword" placeholder="Re-enter your password" v-model="confirmpassword">
                        <i class='bx bx-low-vision'></i>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
                <p v-if="passwordDoNotMatch" style="color: red;">*Passwords do not match. Please try again.</p>
            </div>
            </div>
            <div class="col-6 right_screen">
                <router-view></router-view>
                <BackgroundRight/>
            </div>
        </div>
    </div>
</template>

<script setup>
import BackgroundRight from '@/layouts/BackgroundRight.vue';
import { ref } from 'vue';
const newpassword = ref('');
const confirmpassword = ref('');
const passwordDoNotMatch = ref(false);
const submit = () => {
    if(newpassword.value !== confirmpassword.value){
        passwordDoNotMatch.value = true;
    }else{
    passwordDoNotMatch.value = false;
    console.log('Form submitted with:', { newpassword: newpassword.value });
    }
};

</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
/* .body {
    display: flex;
    align-items: center;
    justify-self: center;
    min-height: 100vh;
    background: #ffffff;
    font-family: 'Poppins', sans-serif;
} */
.left_screen {
    display: flex;
    align-self: center;
}
.text {
    margin: 30px;
    margin-bottom: 60px;
    text-align: left;
}

.container .input {
    width: 100%;
    height: 50px;
    margin: 30px;
    font-size: 16px;
    position: relative;
}

.input input {
    width: 100%;
    height: 100%;
    background: transparent;
    border-radius: 15px;
    border: 2px solid #D9D9D9;
    font-size: 16px;
    padding: 20px 45px 20px 20px;
}

.input i {
    position: absolute;
    right: 20px;
    top: 55%;
    transform: translateY(45%);
    font-size: 20px;
}

.container .btn {
    width: 100%;
    height: 50px;
    margin: 30px;
    font-size: 16px;
    background-color: #3A5B22;
    -webkit-text-fill-color: #ffffff;
    margin: 30px;
    margin-top: 40px;
    border-radius: 15px;
}
</style>