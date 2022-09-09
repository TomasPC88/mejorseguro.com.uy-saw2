<template>
    <div class="col-12 form-group">
        <label class="form-label">{{$t(label)}}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div v-if="icon" class="input-group-text">
                    <i :class="`far fa-${icon}`"></i>
                </div>
            </div>
            <input class="form-control" ref="picker" type="text" v-model="selected" :placeholder="$t(placeholder)"/>
        </div>
    </div>
</template>

<script>
    import BaseFilter from "../../Base/BaseStore";
    import flatpickr from "flatpickr";

    require("flatpickr/dist/l10n/es.js").default.es

    export default {
        name: "date-picker-filter",
        extends: BaseFilter,
        props: {
            label: String,
            name: String,
            placeholder: {
                type: String,
                default: ''
            },
            icon: {
                type: String,
                default: ''
            },
            options: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                instance: {}
            }
        },
        mounted() {
            this.$nextTick(() => {
                this.instance = flatpickr(this.$refs.picker, this.options);
            });
        }
    }
</script>
<style scoped>
    .form-control:read-only {
        background: white;
    }
</style>