<template>
  <div class="v-category-items">
    <div class="v-category-items__title">
      <button @click="sendDataToParent" >{{ category_data.name }}</button >
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

export default {
  name: "v-catalog-items",
  components: {},
  props: {
    category_data: {
      type: Object,
      default() {
        return {}
      }
    }
  },
  data() {
    return {
      selected_id: ''
    }
  },
  computed: {},
  methods: {
    sendDataToParent() {
      this.$emit('sendIdCategory', this.category_data)
    }
  }
}
</script>

<style lang="scss">
.v-category-items__title button{
  padding: 4px 30px 4px 30px;
}

</style>