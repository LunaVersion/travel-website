import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from '../views/LoginPage.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import ResetPassword from '../views/ResetPassword.vue';
import ListNews from '../views/ListNews.vue';
import CreateNews from '../views/CreateNews.vue'
import EditNews from '../views/EditNews.vue'
const routes = [
    { path: '/',  component: LoginPage },
    { path: '/forgotpassword',  component: ForgotPassword },
    { path: '/resetpassword',  component: ResetPassword },
    { path: '/listnews',  component: ListNews },
    { path: '/createnews', component: CreateNews},
    { path: '/editnews/:id', component: EditNews}
  ];

 const router = createRouter({
    history: createWebHistory(),
    routes
  });
export default router;