<template>
  <div class="v-register">
    <div class="v-category__link">
      <router-link :to="{name: 'login'}">
        <div class="v-category__link__to_login">
          Войти
        </div>
      </router-link>
      <router-link :to="{name: 'catalog'}">
        <div class="v-category__link__to_register">
          Вернуться
        </div>
      </router-link>
    </div>
    <h1>Регистрация</h1>
    <form @submit.prevent="register">
      <label for="login" style="display: none;">Логин:</label>
      <input id="login" v-model="login"
             class="v-register__login" placeholder="Логин" required>
      <label for="password" style="display: none;">Пароль:</label>
      <input id="password" type="password"
             class="v-register__passwd"
             v-model="password"
             placeholder="Пароль" required>
      <button class="btn" type="submit">Зарегистрироваться</button>
    </form>
    <p>{{ error }}</p>
  </div>
</template>

<script>
import {mapActions} from 'vuex'

export default {
  name: "v-register",
  data() {
    return {
      login: "",
      password: "",
      password_confirmation: "",
      is_admin: null,
      error: ""
    }
  },
  methods: {
    ...mapActions([
      'REGISTER'
    ]),
    register: function () {
      let data = {
        login: this.login,
        password: this.password,
        is_admin: this.is_admin
      }
      this.REGISTER([this.login, this.password])
          .then((response) => this.$router.push('/'))
          .catch((err) => this.error = err);
    }
  }
}
</script>

<style lang="scss">
form {
  width: 300px;
  margin: 0 auto;
}

.v-register__login {
  width: 90%;
}

.v-register__passwd {
  width: 90%;
}
</style>