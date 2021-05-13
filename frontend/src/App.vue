<template>
  <div id="app">
    <div id="nav">
      <div  v-if="!isLoggedIn" class="v-category__link">
        <router-link :to="{name: 'login'}">
          <div class="v-category__link__to_login">
            Войти
          </div>
        </router-link>
        <router-link :to="{name:'register'}">
          <div class="v-category__link_to_register">
            Регистрация
          </div>
        </router-link>
      </div>
      <span class="v-category__link" v-if="isLoggedIn"> | <a @click="logout">Logout</a></span>
    </div>
    <v-main/>
  </div>
</template>

<script>
import vMain from './components/v-main'

export default {
  name: 'app',
  components: {
    vMain
  },
  computed: {
    isLoggedIn: function () {
      return this.$store.getters.isLoggedIn
    }
  },
  methods: {
    logout: function () {
      this.$store.dispatch('LOGOUT')
          .then(() => {
            this.$router.push('/login')
          })
    }
  },
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
