<template>
    <div id="price-modal" :class="{'show':active}" class="modal fade" :style="{'display':active?'block':'none'}">
        <div class="modal-dialog" style="top:30%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><div class="txt" for='amount'><i class="fas fa-info-circle"></i>&nbsp;{{title}}</div></h5>
                    <button @click="close" type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-5">
                        <component v-if="form" :name="name" :is="form" :data="params"></component>
                    </div>
                </div>
                <div v-if="hasFooter" class="modal-footer">
                    <button @click="ok" type="button" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Channel} from '../vue-app'
    import Contacto from '../components/Formularios/Contact'
    // import RangeFilter from './FilterForm/Filters/RangeFilter'



    export default {
        name: "modal",
        components:{
            Contacto
          //rangeFilter:RangeFilter
        },
        props:{
            hasFooter:{
                type:Boolean,
                default:true
            },
            propTitle:String,
            propForm:String,
            propData:Object
        },
        data(){
           return{
               active:false,
               form:'',
               params:{},
               title:'Información',
               name:''
           }
        },
        created(){
            Channel.$on('openModal',(payload)=>{
                this.active = true;
                this.title = payload.title;
                this.form = payload.form;
                this.params = payload.data;
                this.name=payload.name;
            });
            this.form = this.propForm;
            this.params = this.propData;
        },
        methods:{
            close(){
                this.active=false;
            },
            ok(){
                Channel.$emit('ok');
                this.close();
            }
        }
    }
</script>

<style scoped>

</style>