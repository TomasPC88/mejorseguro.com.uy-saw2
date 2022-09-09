<template>
  <div :id="'group-' + name" class="col-md-12 form-group">
    <div v-if="$t(label)" class="row">
      <div class="col-12">
        <label class="form-label">{{$t(label)}}</label>
      </div>
    </div>
    <div class="row">
      <div class="col-12  px-0">
        <input
          ref="textbox"
          :class="{'pink':searchable}"
          :id="name"
          :name="name"
          v-model="selected"
          type="text"
          :placeholder="$t(placeholder)"
        />
      </div>
    </div>
  </div>
</template>

<script>
import BaseFilter from "../../Base/BaseFilter";

export default {
  name: "text-filter",
  extends: BaseFilter,
  props: {
    label: String,
    placeholder: String,
    searchable: {
      type: Boolean,
      default: false
    }
  },
  mounted() {
    this.$nextTick(() => {
      if (this.searchable)
        this.$refs.textbox.addEventListener("keydown", evt => {
          if (evt.which === 13) this.$store.dispatch("fetchProducts");
        });
    });
  }
};
</script>

<style lang="scss" scoped>
input.pink {
  width: 100%;
  padding: 20px;
  background: #dd489a;
  border-radius: 0;
  color: white;
  transition: background 0.236s linear;
  border: none;
  &::placeholder {
    color: white;
  }
  &:focus {
    background: white;
    color: gray;
    border: none;
    outline: none;
  }
}
</style>
