<template>
    <aside v-sticky="{ stickyTop: 110}" class="">
        <div class="row">
            <div class="col-12">
                <div class="sidebar-box">
                    <div class="sidebar-box__header sidebar-box__header--blue">
                        <h5>{{ $t('form.label.detalles') }}</h5>
                    </div>
                    <div class="sidebar-box__body body">
                        <form class="" action="index.html" method="post">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="information-detail">
                                            <li class="information-detail__item">
                                                <span class="label mb-1">{{$t('ficha.auto.nombre',{nombre: car.marca_modelo})}}</span>
                                            </li>
                                            <li v-if="daysForRent > 0">
                                                <span class="label">{{$t('form.label.dias_alquiler')}}</span>
                                                <span class="value">{{daysForRent}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.transmision')}}:</span>
                                                <span v-if="car.transmision" class="value">{{$t('ficha.auto.transmision_manual')}}</span>
                                                <span v-else
                                                      class="value">{{$t('ficha.auto.transmision_automatica')}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.pasajeros')}}:</span>
                                                <span class="value">{{car.pasajeros}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.puertas')}}:</span>
                                                <span class="value">{{car.puertas}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.motor')}}:</span>
                                                <span class="value">{{car.motor}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.combustible')}}:</span>
                                                <span class="value">{{car.combustible}}</span>
                                            </li>
                                            <li class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.rendimiento')}}:</span>
                                                <span class="value">{{car.rendimiento}} km/l</span>
                                            </li>
                                            <li v-if="garantia" class="information-detail__item">
                                                <span class="label">{{$t('ficha.auto.garantia')}}:</span>
                                                <span class="value">U$S {{garantia}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue my-0 px-2">
                                <div class="container" v-if="isValid && !settlement && daysForRent > 0">
                                    <div v-if="recharge > 0" class="row prices">
                                        <span class="d-none d-sm-none d-md-block d-lg-block d-xl-block txt-orange">{{$t('form.label.vehiculo')}}&nbsp;</span>
                                        <div>
                                            <span class="text-white">U$S&nbsp;</span>
                                            <number style="color:white" class="price"
                                                    :from="10"
                                                    :to="Math.round(recharge)"
                                                    :format="n => n.toFixed(2)"
                                                    :duration=".5"
                                                    :delay=".25"
                                                    easing="Power1.easeOut"/>
                                        </div>
                                    </div>
                                    <div v-if="deducible > 0" class="row prices">
                                        <span class="d-none d-sm-none d-md-block d-lg-block d-xl-block txt-orange">{{$t('form.label.franquicia')}}&nbsp;</span>
                                        <div>
                                            <span class="text-white">U$S&nbsp;</span>
                                            <number style="color:white" class="price"
                                                    :from="0"
                                                    :to="deducible"
                                                    :format="n => n.toFixed(2)"
                                                    :duration=".5"
                                                    :delay=".25"
                                                    easing="Power1.easeOut"/>
                                        </div>
                                    </div>
                                    <hr v-show="$options.filters.isNotEmpty(extras)">
                                    <div v-if="$options.filters.isNotEmpty(extras)" class="row">
                                        <div class="col-12">
                                            <ul class="information-detail--totals">
                                                <transition-group name="fade">
                                                    <li class="information-detail__item" v-for="extra of extras"
                                                        :key="extra.id">
                                                        <span class="label">{{extra.name}}:</span>
                                                        <span class="value">U$S {{Math.round(extra.precio * daysForRent)|priceFormat}}</span>
                                                    </li>
                                                </transition-group>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 form-group btn-box totals">
                                            <div class="price-box">
                                                <span class="d-none d-sm-none d-md-block d-lg-block d-xl-block txt-orange">TOTAL&nbsp;</span>
                                                <div>
                                                    <span class="text-white">U$S&nbsp;</span>
                                                    <number style="color:white" class="price"
                                                            :from="10"
                                                            :to="total"
                                                            :format="n => n.toFixed(2)"
                                                            :duration=".5"
                                                            :delay=".25"
                                                            easing="Power1.easeOut"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="settlement" class="col-12">
                                            <div style="color:#dc7e32" class="alert w-100">
                                                <p class="p-1 text-center m-0 p-0 w-100">
                                                    {{$t('form.label.settlement',[configuraciones.dias_para_convenir_alquiler])}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="configuraciones.first_image" class="row d-none d-sm-none d-md-none d-lg-block">
            <div class="col-12">
                <div class="banner-box mt-5">
                    <img :src="configuraciones.first_image.default.normal" alt="">
                </div>
            </div>
        </div>
    </aside>
</template>

<script>
    import Vue from 'vue'
    import VueNumber from 'vue-number-animation'
    import VueSticky from 'vue-sticky'

    Vue.use(VueNumber)
    Vue.use(VueSticky)

    export default {
        name: "car-file",
        directives: {
            sticky: VueSticky,
        },
        props: {
            car: {
                type: Object,
                required: true
            },
            isValid: {
                type: Boolean,
                default: false
            },
            recharge: {
                type: Number,
                default: 0
            },
            deducible: {
                type: Number,
                default: 0
            },
            daysForRent: {
                type: Number,
                default: 0
            },
            extras: Array,
        },
        computed: {
            total() {
                return this.$store.getters.getSelectedByName('precio_total')
            },
            settlement() {
                return this.$store.getters.getSettlement()
            },
            configuraciones() {
                return this.$store.getters.getItemsByName('configuraciones') || {}
            },
            garantia() {
                return Math.round(this.deducible ? this.car.garantia / 2 : this.car.garantia)
            }
        }
    }
</script>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    .prices span {
        width: calc(100% - 80px);
    }

    .prices {
        font-size: .8rem;
        margin: 5px 0;
    }
</style>