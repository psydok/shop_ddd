<template>
  <div class="v-create">
    <button
        class="v-create__add_item btn" v-on:click="visible=!visible">
      Добавить товар
    </button>
    <v-create-item v-show="visible" @sendPost="showChildInConsole"/>

    <button
        class="v-create__add_category btn" v-on:click="visible=!visible">
      Добавить категорию
    </button>
    <v-create-category v-show="visible" @sendPost="showChildInConsole"/>
  </div>
</template>

<script>
import vCreateItem from './v-create-item'
import vCreateCategory from './v-create-category'

export default {
  name: "v-create",
  data() {
    return {
      visible: false
    }
  },
  components: {
    vCreateItem,
    vCreateCategory
  },
  methods: {
    showChildInConsole() {
      this.visible = !this.visible
    }
  }
}
</script>

<style>

</style>