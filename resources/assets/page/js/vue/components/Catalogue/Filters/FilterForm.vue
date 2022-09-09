<template>
    <form ref="form">
        <div class="container">
            <!--TODO Build a mechanism to remove all filters by themselves in it declaration logic-->
            <div class="row">
                <div class="col-12">
                    <span @click="clearFilters" data-toggle="tooltip" :title="$t('form.label.limpiar_filtros')"
                          class="badge float-right mb-2">
                        <i class="fa fa-trash"></i>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    <select-simple
                            name="tipo_id"
                            :value="tipo"
                            :placeholder="$t('form.placeholder.tipo_vehiculo')"
                            :url="{
                            fetch:types
                        }"
                    />
                </div>
            </div>
        </div>
        <!--<div v-if="priceMin > 0 | priceMax > 0" class="price-box my-3 px-2">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <label for="">{{$t('form.label.precio_x_dia')}}</label>
                        <range-filter
                                :min="priceMin"
                                :max="priceMax"
                                :value="rango"
                                name="price_range"
                        >
                        </range-filter>
                        <div class="price-range">
                            <span>U$S {{priceMin|priceFormat}}</span>
                            <span>U$S {{priceMax|priceFormat}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <label for="delivery-place">
                                {{$t('form.label.transmision')}}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <checkbox-filter :label="$t('form.label.manual')" name="t_manual"/>
                        </div>
                        <div class="col-6 form-group">
                            <div class="form-check">
                                <checkbox-filter :label="$t('form.label.automatica')" name="t_automatica"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group btn-box">
                    <button class="custom-btn" @click.prevent="filter"
                            type="button" name="button">
                        <span v-if="!isFetching">{{$t('form.placeholder.consultar')}}</span>
                        <spinner v-else :css="{height:'30px',width:'30px'}"/>
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
<script>
    import SelectSimple from './SelectSimpleFilter'
    import CheckboxFilter from './CheckboxFilter'
    import RangeFilter from './RangeFilter'
    import Spinner from "../../Spinner"
    import {Channel} from '../../../vue-app'

    export default {
        name: "filter-form",
        components: {
            SelectSimple,
            Spinner,
            RangeFilter,
            CheckboxFilter
        },
        props: {
            name: {
                type: String,
                required: true
            },
            url: {
                type: String,
                required: true
            },
            types: {
                type: String,
                required: true
            },
            priceMax: {
                type: Number,
                required: true
            },
            priceMin: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                filters: {
                    tipo_id: 'tipo_id',
                    t_automatica: 'transmision',
                    t_manual: 'transmision'
                }
            }
        },
        created(){
            const checkGroup = ['t_automatica','t_manual'];
            this.$store.subscribe(({type, payload}) => {
                if (type === 'putInSelected') {
                    checkGroup.filter(k => k !== payload.key).map((k)=>{
                        this.$store.commit('removeSelectedByName', k)
                    })
                }
            });
        },
        methods: {
            clearFilters() {
                Object.keys(this.filters).forEach((f) => {
                    this.$store.commit('removeSelectedByName', f)
                });
                this.filter();
            },
            filter() {
                let filtered = this.inStore;
                Object.entries(this.filters).forEach(([key, value]) => {
                    const selected = this.$store.getters.getSelectedByName(key)
                    if(selected)
                        switch(key){
                            case 't_automatica':
                                filtered = filtered.filter(v => v[value] === 0);
                                break;
                            case 't_manual':
                                filtered = filtered.filter(v => v[value] === 1);
                                break;
                            default:
                                filtered = filtered.filter(v => v[value] == selected)
                        }
                });
                Channel.$emit('paginate', filtered)
            }
        },
        computed: {
            isFetching() {
                return this.$store.getters.isFetching
            },
            tipo() {
                return this.$store.getters.getSelectedByName('tipo_id')
            },
            t_manual(){
                return this.$store.getters.getSelectedByName('t_manual')
            },
            t_automatica(){
                return this.$store.getters.getSelectedByName('t_automatica')
            },
            rango() {
                const inStore = this.$store.getters.getSelectedByName('price_range')
                if (inStore)
                    return inStore

                return [this.priceMin, this.priceMax]
            },
            inStore() {
                return this.$store.getters.getItemsByName(this.name)
            }
        }
    };
</script>

<style lang="scss" scoped>
    .disabled {
        pointer-events: none;
        opacity: 0.8;
    }

    .error-date {
        width: inherit;
        padding: 10px;
        text-align: center;
        outline: solid 1px darkred;
        font-size: .8rem;
    }

    .badge {
        cursor: pointer;

        &:hover {
            color: lighten(#2b336b, 30%)
        }
    }
</style>
