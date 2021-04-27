<template>
  <div class="v-catalog-item">
    <div class="v-catalog-item__props">
      <p class="v-catalog-item__category_id">{{ item_data.category_id.name }}</p>
      <p class="v-catalog-item__name">{{ item_data.name }}</p>
      <p class="v-catalog-item__price">{{ item_data.price }} руб.</p>
    </div>

    <button
        class="v-catalog-item__update_item btn"
        @click="sendDataToParent">
      Редактировать
    </button>
    <button
        class="v-catalog-item__delete_item btn"
        v-on:click="deleteItem(item_data.id)">
      Удалить
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
  props: {
    item_data: {
      type: Object,
      default() {
        return {}
      }
    }
  },
  data() {
    return {}
  },
  computed: {},
  methods: {
    sendDataToParent() {
      this.$emit('sendName', this.item_data.name)
    },
    deleteItem(id) {
      axios('http://192.168.99.101:8000' + '/api/v1/items/' + id, {
        method: "DELETE",
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json"
        }
      }).then((items) => {
      }).catch((error) => {
      })
    }
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