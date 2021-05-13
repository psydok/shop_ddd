<template>
  <div class="v-create">
    <button
        class="v-create__add_item btn" v-on:click="visible=!visible">
      Добавить товар
    </button>
    <v-create-item v-show="visible" @sendPost="showChildInConsole"/>
  </div>
</template>

<script>
import vCreateItem from './v-create-item'

export default {
  name: "v-create",
  data() {
    return {
      visible: false
    }
  },
  components: {
    vCreateItem
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