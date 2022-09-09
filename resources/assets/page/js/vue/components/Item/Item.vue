<template>
    <div class="col-12">
        <a class="listing__item item" href="javascript:;">
            <div class="item__image-box">
                <img :src="item.first_image.default.thumb" alt="">
            </div>

            <div class="item__content-box content-box">
                <div class="content-box__header">
                    <div class="title">
                        <h3>{{item.translation.marca_modelo|capitalize}}</h3>
                    </div>
                    <div v-if="showPrice" class="price-label">
                        <span><span class="txt-orange">U$S {{Math.round(item.precio)|priceFormat}}</span></span>
                    </div>
                </div>
                <div v-if="$options.filters.isNotEmpty(item.translation.descripcion)" class="content-box__body body">
                    <div class="body__desc">
                        <p>
                            {{item.translation.descripcion}}
                        </p>
                    </div>
                </div>
                <div class="content-box__footer">
                    <div class="featured-box">
                        <ul>
                            <li>
                                <label>{{$tc('item.personas',item.pasajeros)}}:</label>
                                <span>{{item.pasajeros}}</span>
                            </li>
                            <li>
                                <label>{{$t('item.puertas')}}:</label>
                                <span>{{item.puertas}}</span>
                            </li>
                            <li>
                                <label>{{$t('item.rendimiento')}}:</label>
                                <span>{{item.rendimiento}}/km</span>
                            </li>
                            <!--<li>
                                <label>{{$t('item.km')}}:</label>
                                <span>{{item.km}} km</span>
                            </li>-->
                            <li>
                                <label>{{$t('item.motor')}}:</label>
                                <span>{{item.motor}} cc</span>
                            </li>
                            <li>
                                <label>{{$t('item.combustible')}}:</label>
                                <span>{{item.combustible}}</span>
                            </li>
                            <li>
                                <label>{{$t('item.transmision')}}:</label>
                                <span v-if="item.transmision">{{$t('item.transmision_manual')}}</span>
                                <span v-else>{{$t('item.transmision_automatica')}}</span>
                            </li>
                            <li>
                                <label>{{$t('item.extras')}}:</label>
                                <span>{{item.extras.map(e => $options.filters.capitalize(e.translation.name)).join(',')}}</span>
                            </li>
                        </ul>
                    </div>
                    <a :href="url.concat(`/${item.translation.friendly_url}`)"><span class="btn-vm">{{ $t('item.leer_mas') }}</span></a>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        name: "item",
        props: {
            url: {
                type:String,
                required:true
            },
            item: Object,
            showPrice:{
                type:Boolean,
                default:false
            }
        }
    };
</script>

<style>
</style>