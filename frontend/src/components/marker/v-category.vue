<template>
  <div class="v-category">
    <div class="v-category-catalog">
      <v-category-items
          v-for="category in CATALOG"
          :key="category._id"
          :category_data="category"
          @sendIdCategory="showChildInConsoleCategory"
      />
    </div>
    <h2>Каталог {{name_catalog}}</h2>
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
      selected_catalog: '1',
      name_catalog: "Все или ничего",
      catalog: [],
      curr_items: []
    }
  },
  computed: {
    ...mapGetters([
      'CATALOG'
    ])
  },
  methods: {
    ...mapActions([
      'GET_CATALOG_FROM_API'
    ]),
    showChildInConsole(data) {
      console.log(data)
    },
    showChildInConsoleCategory(data) {
      this.name_catalog = data.name;
      this.selected_catalog = data._id;
      this.curr_items = this.catalog.filter(i => i._id === this.selected_catalog)[0].items;
    }
  },
  mounted() {
    this.GET_CATALOG_FROM_API()
        .then((response) => {
          if (response.data) {
            this.catalog = Object.values(response.data);
            this.curr_items = this.catalog.data.items;
            console.log('данные из монго');
            console.log(Object.values(response.data));
          }
        });
  }
}
</script>

<style lang="scss">
.v-category {
  &__list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  &__link {
    position: absolute;
    top: 15px;
    right: 10px;
  }
  &__link_to_login {
    padding: $padding * 2;
  }

  &__link_to_register {
    padding: $padding * 2;
  }
}

.v-category-catalog {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-evenly;
  align-items: center;

}
</style>