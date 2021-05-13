<template>
  <div class="authorization">
    <div class="v-category__link">
      <router-link :to="{name:'register'}">
        <div class="v-category__link_to_register">
          Регистрация
        </div>
      </router-link>
      <router-link :to="{name: 'catalog'}">
        <div class="v-category__link__to_register">
          Вернуться
        </div>
      </router-link>
    </div>
    <h1>Авторизация</h1>
    <form class="login" @submit.prevent="signIn">
      <label for="login" style="display: none;">Логин:</label>
      <input id="login" v-model="login"
             class="v-register__login" placeholder="Логин" required>
      <label for="password" style="display: none;">Пароль:</label>
      <input id="password" type="password"
             class="v-register__passwd"
             v-model="password"
             placeholder="Пароль" required>
      <button class="signIn btn" type="submit">Авторизоваться</button>
    </form>
    <p> {{error}}</p>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'

export default {

  name: "v-auth",
  data() {
    return {
      login: "",
      password: "",
      error: ""
    }
  },
  methods: {
    ...mapActions([
      'LOGIN'
    ]),
    signIn: function () {
      let login = this.login;
      let password = this.password;
      this.LOGIN([login, password])
          .then((response) => this.$router.push('/'))
          .catch((err) => this.error=err);

    }
  }
}
</script>

<style>

</style>