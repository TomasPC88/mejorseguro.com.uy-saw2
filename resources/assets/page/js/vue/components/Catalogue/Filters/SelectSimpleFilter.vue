<template>
    <vue-select
            ref="select"
            :placeholder="placeholder|capitalize"
            :label="label"
            :code="code"
            :id="name"
            :options="items"
            v-model="selected"
            :selectable="restriction"
            :clearable="false"
            :value="value"
            :reduce="item => item[code]"
    >
        <template slot="no-options">{{$t('form.placeholder.empty')}}</template>
        <template v-if="isLoading" slot="spinner">
            <spinner :css="{width:'25px',height:'25px'}"></spinner>
        </template>
    </vue-select>
</template>

<script>
    import {Channel} from '../../../vue-app'
    import BaseSelect from '../../Base/BaseSelect'
    import VueSelect from 'vue-select'
    import Spinner from '../../Spinner'

    export default {
        name: "select-simple",
        extends: BaseSelect,
        components: {VueSelect,Spinner},
        props:{
            restriction:{
                type:Function,
                default:function(){
                    return true
                }
            }
        },
        mounted(){

            this.$nextTick(()=>{
                this.$on('clear-selection',()=>{
                    this.$refs.select.clearSelection()
                    this.$refs.select.$props.disabled = true
                })
            })
        },
    }
</script>
<style>
    .disabled{
        pointer-events: none;
    }
</style>