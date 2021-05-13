<template>
  <div class="v-main-wrapper">
    <h3>{{ title }}</h3>
    <v-create/>
    <v-category/>
  </div>
</template>

<script>
import vCategory from './v-category'
import vCreate from './v-create'
import {mapActions, mapGetters} from 'vuex'

export default {
  name: 'v-main-wrapper',
  components: {
    vCategory,
    vCreate,
  },
  props: {},
  data() {
    return {
      title: 'Панель администратора'
    }
  },
  computed: {},
  methods: {
    ...mapActions([
      'CHECK'
    ])
  },
  watch: {},
  mounted() {
    this.CHECK()
        .then(()=>console.log('hello, admin'))
        .catch((err) => {
          this.$router.push('/secure')
        });
  }
}
</script>

<style>
.v-main-wrapper {
  max-width: 900px;
  margin: 0 auto;
}
</style>