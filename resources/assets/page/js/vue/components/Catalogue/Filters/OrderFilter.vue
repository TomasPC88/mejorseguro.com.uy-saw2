<template>
  <form class="form-inline">
    <div class="form-group d-flex flex-wrap align-items-center">
      <p class="mb-0 mr-3">{{$t('labels.sort')}}</p>
      <select :name="name" v-model="selected">
        <option :value="{column:'price',direction:'asc'}">Menor Precio</option>
        <option :value="{column:'price',direction:'desc'}">Mayor Precio</option>
        <option :value="{column:'translation.description',direction:'asc'}">A-Z</option>
        <option :value="{column:'translation.description',direction:'desc'}">Z-A</option>
      </select>
    </div>
  </form>
</template>

<script>
import BaseFilter from "../../Base/BaseFilter";

export default {
  name: "order-filter",
  extends: BaseFilter,
  props: {
    name: {
      type: String,
      default: "order"
    }
  },
  watch: {
    selected() {
      this.$store.dispatch("fetchProducts",this);
    }
  }
};
</script>

<style scoped>
</style>