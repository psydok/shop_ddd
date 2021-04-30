<template>
  <div class="v-category">
    <div class="v-category-catalog">
      <v-category-items
          v-for="category in CATEGORIES"
          :key="category.id"
          :category_data="category"
          @sendIdCategory="showChildInConsoleCategory"
      />
    </div>
    <h1>Каталог</h1>
    <div class="v-catalog__list">
      <v-category-item
          v-for="item in this.curr_items"
          :key="item.id"
          :item_data="item"
          @sendName="showChildInConsole"
      />
    </div>
  </div>
</template>

<script>
import vCategoryItem from './v-category-item'
import vCategoryItems from './v-category-items'
import {mapActions, mapGetters} from 'vuex'

export default {
  name: "v-category",
  components: {
    vCategoryItems,
    vCategoryItem,
  },
  props: {},
  data() {
    return {
      selected_category: '1',
      all_items: [],
      curr_items: []
    }
  },
  computed: {
    ...mapGetters([
      'CATEGORIES',
      'ITEMS'
    ])
  },
  methods: {
    ...mapActions([
      'GET_CATEGORIES_FROM_API',
      'GET_ITEMS_FROM_API',
      'SET_ITEMS'
    ]),
    showChildInConsole(data) {
      console.log(data)
    },
    showChildInConsoleCategory(data) {
      console.log(data);
      this.selected_category = data;
      this.curr_items = this.all_items.filter(i => i.category_id.id === this.selected_category)
    }
  },
  mounted() {
    this.GET_CATEGORIES_FROM_API()
        .then((response) => {
          if (response.data) {
            console.log('data arrived!');
          }
        });
    this.GET_ITEMS_FROM_API()
        .then((response) => {
          if (response.data) {

            this.all_items = response.data;
            this.curr_items = this.all_items;
            console.log('data arrived!');
          }
        });
  }
}
</script>

<style lang="scss">
.v-catalog {
  &__list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }
}

.v-category-catalog {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-evenly;
  align-items: center;

}
</style>