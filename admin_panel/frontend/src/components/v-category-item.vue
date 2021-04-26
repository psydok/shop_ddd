<template>
  <div class="v-catalog-item">
    <p class="v-catalog-item__name">{{ category_data.name }}</p>
    <button
        class="v-catalog-item__update_item btn"
        @click="sendDataToParent">Редактировать
    </button>
    <button
        class="v-catalog-item__delete_item btn">Удалить
    </button>
  </div>
</template>

<script>
export default {
  name: "v-catalog-item",
  props: {
    category_data: {
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
      this.$emit('sendName', this.category_data.name)
    }
  }
}
</script>

<style lang="scss">
.v-catalog-item {
  flex-basis: 25%;
  box-shadow: 0 0 8px 0 #e0e0e0;
  padding: $padding*2;
  margin-bottom: $margin*2;
}
</style>