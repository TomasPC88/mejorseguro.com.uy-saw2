<template>
    <div class="component">
        <a v-if="add && check" @click="openModal()" class="button wo-add-btn is-pulled-right" href="#!">
            <i class="fa fa-plus"></i>
        </a>
        <div class="field field-choices" :data-validation="rules" :data-alias="title">
            <input type="hidden" :name="name" :value="selected">
            <slot name="label"></slot>
            <vue-select
                    ref="select"
                    :label="label"
                    :disabled="disabled"
                    :code="code"
                    :id="name"
                    :options="items"
                    v-model="selected"
                    :clearable="false"
                    :value="value"
                    :reduce="item => item[code]"
            >
                <template slot="no-options">No hay ningún elemento seleccionable</template>
                <template v-if="isLoading" slot="spinner">
                    <spinner :css="{width:'25px',height:'25px'}"></spinner>
                </template>
            </vue-select>
        </div>
    </div>
</template>

<script>
    import {Channel} from '../../vue-app'
    import BaseSelect from '../Base/BaseSelect'
    import VueSelect from 'vue-select'
    import Spinner from '../reusable/Spinner'

    export default {
        name: "select-simple",
        extends: BaseSelect,
        components: {VueSelect,Spinner},
        created(){
            Channel.$on('saved', (params) => {
                if (params.from === this._uid)
                    this.selected = params.response.data.data[this.code];
            });
        },
        mounted(){
            this.$nextTick(()=>{
                this.$on('clear-selection',()=>{
                    this.$refs.select.clearSelection()
                })
            })
        },
        watch: {
            selected(v) {
                if (this.shared_store.is && this.shared_store.substract){
                    const index = this.items.findIndex(i => i[this.code] !== v)
                    if(index !== -1)
                        this.items[index] = Object.assign({disabled:true},this.items[index])
                }
            }
        }
    }
</script>