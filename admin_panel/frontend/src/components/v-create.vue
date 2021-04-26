<template>
  <div class="v-create">
    <h1>Добавить категорию</h1>
    <v-create-item/>
  </div>
</template>

<script>
import vCreateItem from './v-create-item'

export default {
  name: "v-create",
  components: {
    vCreateItem
  }
}
</script>

<style>

</style>