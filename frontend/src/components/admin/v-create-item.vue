<template>
  <div class="v-create-item">
    <div class="v-create-item__inputs">
      <p>Категория: {{ selected }}</p>
      <label>
        <select class="v-create-item__selected" v-model="selected" required>
          <option disabled value="">Выберите один из вариантов</option>
          <option
              v-for="category in CATEGORIES"
              :key="category.id"
              :value="category.id"
              v-text="category.name"
          />
        </select>
      </label>
    </div>
    <div class="v-create-item__inputs">
      <div>
        <p>Название товара:</p>
        <input class="v-create-item__item"
               v-model="item_name"
               placeholder="Название товара"
               required>
      </div>
      <div>
        <p>Цена: {{ item_price }} руб.</p>
        <input class="v-create-item__item"
               type="number"
               v-model="item_price"
               value="10"
               min="1"
               required>
      </div>
      <div>
        <p>Ссылка на картинку: </p>
        <input class="v-create-item__item"
               type="url"
               v-model="item_img"
               placeholder="https://... .jpg"
               pattern="https://.*">
      </div>
    </div>
    <button class="v-create-item__send_create btn" v-on:click="addItem" @click="sendDataToParent">OK</button>
  </div>
</template>

<script>
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

import {mapGetters} from "vuex";

export default {
  name: "v-create-item",
  props: {},
  data() {
    return {
      selected: '',
      item_name: '',
      item_price: '',
      item_img: ''
    }
  },
  computed: {
    ...mapGetters([
      'CATEGORIES',
      'ITEMS',
      'TOKEN'
    ])
  },
  methods: {
    addItem(event) {
      const data = {
        name: this.item_name,
        price: this.item_price,
        category_id: this.selected,
        img_link: this.item_img
      };
      axios('http://' + 'localhost:8060' + '/admin/items', {
        method: "POST",
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json",
          'Authorization': "Bearer " + this.TOKEN
        },
        data: data
      })
          .then((items) => {
            alert('Sent!');
          })
          .catch((error) => {
            alert('Error: ' + error);
            return error;
          })
    },
    sendDataToParent() {
      this.$emit('sendPost')
    }
  }
}
</script>

<style lang="scss">
.v-create-item {
  &__inputs {
    margin-bottom: $margin*2;
  }

  &__inputs p {
    margin: auto;
  }

  &__selected {
    width: 320px;
    padding: $padding;
  }

  &__item {
    width: 300px;
    margin-bottom: $margin;
    padding: $padding;
  }

  display: block;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
}
</style>