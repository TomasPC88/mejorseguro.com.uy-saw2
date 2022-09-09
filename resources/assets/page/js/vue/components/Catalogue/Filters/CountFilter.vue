<template>
  <div class="col-12 form-group">
    <div class="row mb-2">
      <div class="col-12">
        <label class="form-label">{{$t(label)}}</label>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <ul class="check-list">
          <li
            :class="{'active':selected==number}"
            class="check-list__item"
            @click="selected = number"
            v-for="number of numbers"
            :key="number"
          >
            <span>{{number}}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import BaseFilter from "../../Base/BaseFilter";

export default {
  name: "count-filter",
  extends: BaseFilter,
  props: {
    name: String,
    label: String,
    numbers: Array
  }
};
</script>

<style scoped>
</style>