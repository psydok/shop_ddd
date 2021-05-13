import Vue from 'vue'
import App from './App.vue'
import store from './vuex/store'
import Axios from 'axios'
import router from "@/router/router";

Vue.config.productionTip = false
Vue.prototype.$http = Axios;
const token = localStorage.getItem('token')
if (token) {
  Vue.prototype.$http.defaults.headers.common['Authorization'] = "Bearer" + token
}
new Vue({
  render: h => h(App),
  store,
  router
}).$mount('#app')
