import Vue from 'vue'
import Router from 'vue-router'
import vCategory from '../components/marker/v-main-market.vue'
import vCategoryAdmin from '../components/admin/v-main-wrapper.vue'
import vLogin from '../components/v-auth.vue'
import vSecure from '../components/v-secure.vue'
import vRegister from '../components/v-register.vue'

Vue.use(Router);

let router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'catalog',
            component: vCategory
        },
        {
            path: '/admin',
            name: 'admin',
            component: vCategoryAdmin
        },
        {
            path: '/login',
            name: 'login',
            component: vLogin
        },
        {
            path: '/register',
            name: 'register',
            component: vRegister
        },
        {
            path: '/secure',
            name: 'secure',
            component: vSecure,
            meta: {
                requiresAuth: true
            }
        }
    ]
});

export default router
