<template>
    <div class="breadcrumb-ficha card card-body">
        <ul id="ul-breadcrum">
            <li><a href="/">Home</a><i class="mdi mdi-chevron-right"></i></li>
            <li @click="go(key)" v-for="(key,index) of keys" :key="index">
                <a href="#" :data-value="key">{{key.name}}</a><i class="mdi mdi-chevron-right"></i>
            </li>
        </ul>
        <div class="redes-sociales">
            <div id="share"></div>
        </div>
    </div>
</template>

<script>
    import {Channel} from '../vue-app'

    export default {
        name: "breadcrum",
        props: {
            keys:Array,
            to: String
        },
       methods:{
            go(key){
                this.save('filters',{[key.id]:key.value});
                window.location=this.to;
            }
       }
    }
</script>

<style lang="scss" scoped>
    .breadcrumb-ficha {
        ul {
            span {
                display: inline;
            }
        }
    }
</style>