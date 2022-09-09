<template>
    <section>
        <div class="row pb-2 pb-sm-3 pb-md-4 pb-lg-4 pb-xl-4">
            <div class="col-12">
                <div class="fleet-file__header">
                    <h1>
                        <!--TODO Setear color naranja a mitad del nombre el auto-->
                        <!--                        <span class="txt-orange">2016</span>-->
                        {{car.marca_modelo}}
                    </h1>
                    <!--<div class="price-box">
                        <span class="d-none d-sm-none d-md-block d-lg-block d-xl-block">{{$t('item.price_label')}}</span>
                        <span class="price">${{car.precio|priceFormat}}</span>
                        <span class="d-block d-sm-block d-md-none d-lg-none d-xl-none">&nbsp;/&nbsp;{{$t('item.price_label')}}</span>
                    </div>-->
                </div>
            </div>
        </div>
        <div class="row pb-5 pb-sm-5 pb-md-5 pb-lg-3 pb-xl-3">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-9 pb-4 pb-sm-4 pb-md-4 pb-lg-0 pb-xl-0">
                <div class="row">
                    <div class="col-12">
                        <slot name="flickity"></slot>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <locations @validated="v => isValid = v"
                                   @changed="consult"
                                   @deducible="v => deducible = v"
                                   @days="v => daysForRent = v"
                                   :floating="false"
                                   :diff-in-days="daysForRent"
                                   :routes="{
                                        feriados:routes.feriados,
                                        restricciones:routes.restricciones,
                                        configuraciones:routes.configuraciones,
                                        lugares:routes.lugares
                        }"/>
                    </div>
                </div>
                <div v-if="$options.filters.isNotEmpty(car.extras)" class="row">
                    <div class="col-12">
                        <check-group
                                :value="[]"
                                name="extras"
                                title="extras"
                                :items="car.extras">
                        </check-group>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <p>{{$t('labels.garantia')}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="custom-block">
                            <div class="custom-block__header">
                                <h3>{{ $t('form.label.title') }}</h3>
                            </div>
                            <div class="custom-block__body">
                                <div class="formulario">
                                    <personal-data
                                            :can="isValid || hasSettlement"
                                            :routes="routes"
                                            :url="routes.reservar"
                                            :use-recaptcha="true"
                                            :recaptcha-key="recaptchaKey"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="position: sticky; top:0" class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3">
                <car-file @consult="consult" :car="car" :is-valid="isValid" :extras="extras" :recharge="recharge" :deducible="deducible"
                          :days-for-rent="daysForRent"/>
            </div>
        </div>
    </section>
</template>

<script>
    import CheckGroup from './CheckGroup'
    import Locations from './Locations'
    import PersonalData from "./PersonalData"
    import CarFile from './CarFile'

    export default {
        name: "reservation",
        components: {
            PersonalData,
            CheckGroup,
            Locations,
            CarFile
        },
        props: {
            car: {
                type: Object,
                required: true
            },
            routes: {
                type: Object,
                required: true
            },
            recaptchaKey: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                isValid: false,
                recharge: 0,
                deducible: 0,
                daysForRent: 0
            }
        },
        mounted() {
            this.$store.commit('putInSelected', {
                key: 'id',
                value: this.car.id
            });

            this.total = this.car.precio;

            this.$watch(vm => [vm['recharge'], vm['extras'], vm['deducible']], val => {
                const [recharge, extras, deducible] = val;
                this.total = Math.round(recharge + deducible + extras.reduce((acc, e) => acc + Math.round(e.precio  * this.daysForRent), 0))
            });
        },
        methods: {
            consult() {
                if (this.isValid && !this.hasSettlement) {
                    axios.get(this.routes.consultar_precio, {
                        params: this.$store.getters.getAllSelected()
                    }).then(({data}) => {
                        const {days, total} = data.data
                        this.recharge = total;
                        this.daysForRent = days;
                        this.$store.commit('setFiltered', true);
                    }).catch((e) => {
                        console.log(e);
                        this.recharge = 0;
                        this.daysForRent = 0;
                        this.$store.commit('setFiltered', false);
                    });
                    return
                }
                this.recharge = 0
            }
        },
        computed: {
            extras() {
                const selected = this.$store.getters.getSelectedByName('extras') || [];
                return this.car.extras.filter(e => selected.indexOf(e.id) !== -1) || []
            },
            total: {
                set(v) {
                    this.$store.commit('putInSelected', {
                        key: 'precio_total',
                        value: v
                    })
                },
                get() {
                    return this.$store.getters.getSelectedByName('precio_total')
                }
            },
            configuraciones() {
                return this.$store.getters.getItemsByName('configuraciones') || {}
            },
            hasSettlement() {
                return this.$store.getters.getSettlement()
            }
        },
        watch: {
            hasSettlement(v) {
                if (v)
                    this.total = 0;
            }
        }
    }
</script>

<style lang="scss">
    ul[role="listbox"] {
        li {
            width: 100% !important;
            font-size: .8rem;
        }
    }
</style>