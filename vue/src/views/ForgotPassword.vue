<template>
    <div class="container">
        <div class="row">
            <div class="col-6 left_screen">
                <div>
                <div class="text">
                    <h1>Forgot password!</h1>
                    <p>Please enter your email to reset the password</p>
                </div>
                <form @submit.prevent="submit()">
                    <div class="input">
                        <label class="label" for="email">Email address</label> <br>
                        <input type="text" 
                            id="email" 
                            placeholder="Enter your email" 
                            v-model="email"
                            @blur="validate()"
                            v-bind:class = "{'is-invalid':errors.emailError}"
                            >
                        <div class="invalid-feedback" v-if="errors.emailError" >{{ errors.emailError }}</div>
                        </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
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
import { reactive, ref } from 'vue';
import api from '../api';
const email = ref('');
const errors = reactive({
    emailError: '',
})
const validate = () => {
    errors.emailError = '';
    if(!email.value) {
        errors.emailError = "Email is required !"
    } else if(!validateEmail(email.value)) {
        errors.emailError = "Email must be in correct format !"
    }
}
// eslint-disable-next-line no-unused-vars
const validateEmail = (inputEmail) => {
    if (/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/.test(inputEmail)) {
        return true;
    }
    return false;
}
const submit = async () => {
    validate();
    if (!errors.emailError) {
        try {
            const response = await api.post('/forgot-password', {
                email: email.value,
            });
            console.log('Password reset request successful:', response.data);
        } catch (error) {
            console.log('Error during password reset request:', error.response.data);
        }
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
