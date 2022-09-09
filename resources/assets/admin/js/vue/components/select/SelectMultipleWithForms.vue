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
                    :closeOnSelect="false"
                    :label="label"
                    :disabled="disabled"
                    :code="code"
                    :id="name"
                    :options="items"
                    v-model="selected"
                    :selectable="restriction"
                    :clearable="true"
                    :multiple="true"
                    :selectOnTab="true"
                    :reduce="item => item[code]"
            >
                <template slot="no-options">No hay ningún elemento seleccionable</template>
                <template v-if="isLoading" slot="spinner">
                    <spinner :css="{width:'25px',height:'25px'}"></spinner>
                </template>
            </vue-select>
        </div>
        <component v-if="hasForm" v-for="item of filtered" :key="item[code]" :id="item[code]"
                   :data="formData[item[code]]"
                   :title="item[label]" :is="form">
            <template slot="footer">
                <hr>
            </template>
        </component>
    </div>
</template>

<script>
    import {Channel} from '../../vue-app'
    import BaseSelect from '../Base/BaseSelect'
    import VueSelect from 'vue-select'
    import Spinner from '../reusable/Spinner'

    export default {
        name: "select-multiple",
        extends: BaseSelect,
        components: {
            VueSelect,
            Spinner
        },
        props: {
            putAllTags: {
                type: Boolean,
                default: false
            },
            restriction:{
                type:Function,
                default:function(){
                    return true
                }
            },
            hasForm: {
                type: Boolean,
                default: false
            },
            form: String,
            formData: [Object, Array]
        },
        created() {
            Channel.$on('saved', (params) => {
                if (params.from === this._uid)
                    this.selected.push(params.response.data.data[this.code]);
            });

            Channel.$on('synced', (params) => {
                if (params.from === this._uid)
                    this.items = params.response.data.data;
            });
        },
        mounted() {
            this.$nextTick(() => {
                this.$on('clear-selection', () => {
                    this.$refs.select.clearSelection();
                    if (!this.putAllTags) {
                        this.selected = this.items.map(i => i[this.code]).filter(i => this.value.indexOf(i) !== -1)
                    }
                })
            })
        },
        computed: {
            filtered() {
                return this.items.filter((i) => this.selected.indexOf(i[this.code]) !== -1);
            }
        },
        watch: {
            items(v) {
                if (this.putAllTags)
                    this.selected = v.map(i => i[this.code])
            },
            /*sharedSelectedStack(v) {
                if (this.shared_store.is && this.shared_store.substract)
                    this.items = this.items.map(i => {
                        i.disabled = (v.indexOf(i[this.code]) !== -1)
                        return i
                    })
            }*/
        }
    }
</script>
<style>
    .vs__dropdown-option--selected {
        display: none;
    }
</style>
