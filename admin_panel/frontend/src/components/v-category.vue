<template>
  <div class="v-category">
    <h1>Категории</h1>
    <div class="v-catalog__list">
      <v-category-item
          v-for="item in ITEMS"
          :key="item.id"
          :item_data="item"
          @sendName="showChildInConsole"
      />
    </div>
  </div>
</template>

<script>
import vCategoryItem from './v-category-item'
import {mapActions, mapGetters} from 'vuex'

export default {
  name: "v-category",
  components: {
    vCategoryItem,
  },
  props: {},
  data() {
    return {}
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
        'GET_ITEMS_FROM_API'
    ]),
    showChildInConsole(data) {
      console.log(data)
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
</style>