<template>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <label for="">{{label}}:</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="filtros-ls">
                        <li v-for="(item,index) of items" :key="index">{{item.label}} <i @click="pull(item)" class="mdi mdi-close"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Channel} from "../../../vue-app";

    export default {
        name: "tag-filter",
        props:{
            label:String,
            items:Array
        }
    }
</script>

<style scoped>

</style>