<template>
  <div class="v-main-wrapper">
    <h3>{{ title }}</h3>
    <v-category/>
  </div>
</template>

<script>
import vCategory from './v-category'

export default {
  name: 'v-main-wrapper',
  components: {
    vCategory
  },
  props: {},
  data() {
    return {
      title: 'SHOP'
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