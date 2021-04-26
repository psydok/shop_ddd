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
  methods: {},
  watch: {},
  mounted() {
    console.log('hello')
  }
}
</script>

<style>
.v-main-wrapper {
  max-width: 900px;
  margin: 0 auto;
}
</style>