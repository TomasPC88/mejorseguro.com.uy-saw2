<template>
    <vue-slider ref="slider" v-model="selected"
                :min="min"
                :lazy="true"
                :max="max"
                :value="value"
                :interval="step"
                :width="'100%'"
                :useKeyboard="true"
                :formatter="'U$S ' + '{value}'"
                :processStyle="{'backgroundImage': '-webkit-linear-gradient(left,#3498db,#f05b72)'}"
    ></vue-slider>
</template>

<script>
    import {Channel} from "../../../vue-app"
    import VueSlider from 'vue-slider-component'
    import BaseStore from '../../Base/BaseStore'

    export default {
        name: "range-filter",
        extends: BaseStore,
        components: {
            vueSlider: VueSlider
        },
        props: {
            min: {
                type: Number,
                required: true
            },
            max: {
                type: Number,
                required: true
            },
            step: {
                type: Number,
                default: 0.001
            },
            value: {
                type: [Number, Array],
                default: 0
            },
            name: String,
        }
    }
</script>

<style scoped>

</style>