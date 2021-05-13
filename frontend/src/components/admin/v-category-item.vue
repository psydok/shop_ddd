<template>
  <div class="v-catalog-item">
    <div class="v-catalog-item__props">
      <p class="v-catalog-item__name">
        <input type="text" ref="item_name" :value="item_data.name" :disabled="!isEditing"
               :class="{view: !isEditing}"></p>
      <p class="v-catalog-item__price">
        <input type="text" ref="item_price" :value="item_data.price"
               :disabled="!isEditing"
               :class="{view: !isEditing}"></p>
      <p>Изображение</p>
      <p class="v-catalog-item__img_link">
        <input type="text" ref="item_img_link" :value="item_data.img_link"
               :disabled="!isEditing"
               :class="{view: !isEditing}">
      </p>
      <p class="v-catalog-item__price">{{ item_data.price }} руб.</p>
    </div>
    <button
        @click="isEditing = !isEditing" v-if="!isEditing"
        class="v-catalog-item__update_item btn">
      Редактировать
    </button>
    <button class="btn" @click="save(item_data.id)" v-else-if="isEditing">
      Сохранить
    </button>
    <button class="btn" v-if="isEditing" @click="isEditing = false">Отмена</button>

    <button v-else-if="!isEditing"
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
import {mapActions, mapGetters} from 'vuex'
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
  computed: {
    ...mapGetters([
      'TOKEN'
    ])
  },
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
    },
    deleteItem(id) {
      axios('http://' + 'localhost:8060' + '/admin/items/' + id, {
        method: "DELETE",
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json",
          'Authorization': "Bearer " + this.TOKEN

        }
      }).then((items) => {
        alert("Удалено! Обновите")
      }).catch((error) => {
      })
    },
    updatedItem(id) {
      const data = {
        name: this.item_name,
        price: this.item_price,
        category_id: this.item_data.category_id.id,
        img_link: this.item_img
      };
      console.log(data);
      axios('http://' + 'localhost:8060' + '/admin/items/' + id, {
        method: "PUT",
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json",
          'Authorization': "Bearer " + this.TOKEN
        },
        data: data
      }).then((items) => {
        alert("Обновлено! Обновите")
      }).catch((error) => {
      })
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