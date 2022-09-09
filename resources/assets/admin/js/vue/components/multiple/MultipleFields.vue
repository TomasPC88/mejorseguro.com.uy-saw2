<template>
   <div class="columns">
       <div class="column">
           <h1 class="title">{{ title }}</h1>
       </div>
       <div class="column">
           <div class="tabs is-small">
               <ul>
                   <li @click="localeActive=index" v-for="(locale,index) of locales" :key="index" :class="{'is-active':index===localeActive}"><a>{{locale}}</a></li>
               </ul>
           </div>
       </div>
       <div class="columns" v-for="(piece,index) of items.splice(0,4)" :key="index">
           <div class="column" v-for="item of piece" :key="item.id">
               {{item.id}}
           </div>
       </div>
   </div>
</template>

<script>
    export default {
        name: "multipleFields",
        props:{
            add:Boolean,
            title:String,
            locales:Array,
            modalForm:Object,
            url:Object
        },
        data(){
            return {
                items:[],
                localeActive:0
            }
        },
        mounted(){

        },
        methods:{
            load(){
                axios.get(`${this.baseUrl}/${this.url.fetch}`)
                    .then((response)=>{
                        this.items=response.data;
                    })
                    .catch((e)=>{
                        console.log(e);
                        this.toast('Ocurrió un error en la transacción.');
                    })
            }
        }
    }
</script>

<style scoped>

</style>