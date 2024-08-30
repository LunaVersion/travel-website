<template>
    <div class="container">
        <div class="row">
            <div class="col-6 left_screen">
                <div>
                <div class="text">
                    <h1>Welcome back!</h1>
                    <p>Enter your Credentials to access your account</p>
                </div>
                <form action="" @submit.prevent="submit()">
                    <div class="input">
                        <label class="label" for="email">Email address</label> <br>
                        <input type="text" 
                            id="email" 
                            placeholder="Enter your email" 
                            v-model="credentials.email"
                            v-bind:class = "{'is-invalid':errors.emailError}"
                            > <br>
                        <div class="invalid-feedback" v-if="errors.emailError" >{{ errors.emailError }}</div>
                    </div>
                    <div class="input">
                        <label class="label" for="password">Password</label> <br>
                        <input type="text" 
                            id="password" 
                            placeholder="Enter your password" 
                            v-model="credentials.password"
                            v-bind:class = "{'is-invalid':errors.passwordError}"
                            >
                        <div class="invalid-feedback" v-if="errors.passwordError" >{{ errors.passwordError }}</div>
                        <i class='bx bx-low-vision'></i>
                    </div>
                    <div class="forgotpassword">
                        <router-link to="/forgotpassword">Forgot Password</router-link> <br>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
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
import { reactive } from 'vue';
import api from '../api';
import { useRouter } from 'vue-router';


const credentials  = reactive({
    email: 'lanhuong30032003@gmail.com',
    password: '12345678'
});

const errors = reactive({
    emailError: '',
    passwordError: ''
});

const validateEmail = (inputEmail) => {
    const emailPattern = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/;
    return emailPattern.test(inputEmail);
};

const validate = () => {
    let isValid = true;
    errors.emailError = '';
    errors.passwordError = '';

    if (!credentials .email) {
        errors.emailError = 'Email is required!';
        isValid = false;
    } else if (!validateEmail(credentials.email)) {
        errors.emailError = 'Email must be in correct format!';
        isValid = false;
    }

    if (!credentials .password) {
        errors.passwordError = 'Password is required!';
        isValid = false;
    } else if (credentials .password.length < 8) {
        errors.passwordError = 'Password must be longer than 8 characters!';
        isValid = false;
    }

    return isValid;
};

const router = useRouter();

const submit = async () => {
    if (validate()) {
        try {
            const response = await api.post('/login', {
                email: credentials.email,
                password: credentials.password
            });

            console.log('Login successful:', response.data);
            router.push('/listnews');
            // Xử lý thành công, ví dụ: chuyển hướng người dùng
        } catch (error) {
            console.log('Login error:', error.response.data);
            if (error.response && error.response.status === 401) {
                errors.passwordError = 'Invalid credentials!';
            } else {
                alert('An error occurred while trying to login.');
            }
        }
    }
};

// const validate = () => {
//     let isValid = true;
//     this.errors = {
//         emailError: '',
//         passwordError: ''
//     }
//     if(!this.product.email) {
//         this.errors.emailError = "Email is required !"
//         isValid = false
//     } else if(!this.validateEmail(this.product.email)) {
//         this.errors.emailError = "Email must be in correct format !"
//         isValid = false
//     }

//     if(!this.product.password) {
//         this.errors.passwordError = "Password is required !"
//         isValid = false
//     } else if(this.product.password.length < 8) {
//         this.errors.passwordError = "Password must be longer than 8 character !"
//         isValid = false
//     }
//     return isValid
// }
// // eslint-disable-next-line no-unused-vars
// const validateEmail = (inputEmail) => {
//     if (/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/.test(inputEmail)) {
//         return true;
//     }
//     return false;
// }
// const submit = () => {
//     if(this.validate()) {
//         console.log()
//     }
//     console.log(this.errors)
// }
// const submit = () => {
//     // this.validate()
//     console.log(this.erros)
// }
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

.forgotpassword a {
    text-decoration: none;
}

.container .forgotpassword {
    display: flex;
    justify-content: end;
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
