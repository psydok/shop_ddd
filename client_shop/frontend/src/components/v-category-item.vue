<template>
  <div class="v-catalog-item">
    <div class="v-catalog-item__props">
      <p class="v-catalog-item__name">
        {{item_data.name}}</p>
      <p class="v-catalog-item__price">
        {{item_data.price}}</p>
      <p>Изображение</p>
      <p class="v-catalog-item__img_link">
        {{item_data.img_link}}
      </p>
    </div>

    <button
            class="v-catalog-item__delete_item btn"
            v-on:click="">
      Добавить в корзину
    </button>
  </div>
</template>

<script>
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

export default {
  name: "v-catalog-item",
  components: {},
  props: {
    item_data: {
      type: Object,
      default() {
        return {}
      }
    }
  },
  data() {
    return {
      selected: '',
      item_name: '',
      item_price: '',
      item_img: ''
    }
  },
  computed: {},
  methods: {
    sendDataToParent() {
      this.$emit('sendName', this.item_data.name)
    },

  }
}
</script>

<style lang="scss">
.v-catalog-item {
  &__props {
  }

  max-width: 300px;
  flex-basis: 25%;
  box-shadow: 0 0 8px 0 #e0e0e0;
  padding: $padding*2;
  margin-bottom: $margin*2;
}

button {
  flex-basis: 25%;
  box-shadow: 0 0 8px 0 #e0e0e0;
  padding: $padding*2;
  margin-bottom: $margin*2;
}
</style>