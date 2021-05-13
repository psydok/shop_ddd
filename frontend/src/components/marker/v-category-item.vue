<template>
  <div class="v-catalog-item">
    <div class="v-catalog-item__props">
      <img class="v-catalog-item__img_link"
           :src="item_data.img_link"
      />
      <p class="v-catalog-item__name">
        {{ item_data.name }}</p>
      <p class="v-catalog-item__price">{{ item_data.price }} руб.</p>
    </div>
    <button
        class="v-catalog-item__add_item btn">
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
      isEditing: false,
      selected: '',
      item_name: '',
      item_price: '',
      item_img: ''
    }
  },
  computed: {},
  methods: {
    save(id) {
      this.item_name = this.$refs['item_name'].value;
      this.item_price = this.$refs['item_price'].value;
      this.item_img = this.$refs['item_img_link'].value;

      this.updatedItem(id);
      this.isEditing = !this.isEditing;
    },
    sendDataToParent() {
      this.$emit('sendName', this.item_data.name)
    }
  }
}
</script>

<style lang="scss">
.v-catalog-item {
  &__props {
  }

  &__img_link {
    max-width: 300px;
  }

  &__add_item {
    border: 4px double #ddd;
    color: black;
    background: #fff;
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