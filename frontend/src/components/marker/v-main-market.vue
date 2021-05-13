<template>
  <div class="v-main-market">
    <h3>{{ title }}</h3>
    <v-category/>
  </div>
</template>

<script>
import vCategory from './v-category'

export default {
  name: 'v-main-market',
  components: {
    vCategory
  },
  props: {},
  data() {
    return {
      title: 'Маркет плей'
    }
  },
  computed: {},
  methods: {},
  watch: {},
  mounted() {
  }
}
</script>

<style>
.v-main-wrapper {
  max-width: 900px;
  margin: 0 auto;
}
</style>