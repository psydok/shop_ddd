<template>
  <div class="v-create-item">
    <div class="v-create-item__inputs">
      <div>
        <p>Название категории:</p>
        <input class="v-create-item__item"
               v-model="category_name"
               placeholder="Название категории"
               required>
      </div>

    </div>
    <button class="v-create-item__send_create btn" v-on:click="addCategory" @click="sendDataToParent">OK</button>
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
      category_name: ''
    }
  },
  computed: {
    ...mapGetters([
      'TOKEN'
    ])
  },
  methods: {
    addCategory(event) {
      if (this.category_name === "")
        return ;

      const data = {
        name: this.category_name
      };
      axios('http://' + 'localhost:8060' + '/admin/categories', {
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