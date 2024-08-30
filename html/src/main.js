import { createApp } from 'vue'
import App from './App.vue'
import 'bootstrap/dist/css/bootstrap.css'
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js'
import router from './router/index.js';
import { createPinia } from 'pinia';


const app = createApp(App);
const pinia = createPinia();
app.use(router)
app.use(bootstrap)
app.use(pinia)
app.mount('#app')


