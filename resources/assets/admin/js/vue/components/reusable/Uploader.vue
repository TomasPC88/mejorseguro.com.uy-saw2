<template>
    <div class="field">
        <div class="file-uploader" :data-alias="name" :data-video="params.video" :data-limit="params.limit" :data-type="params.type"></div>
    </div>
</template>

<script>
    import {Channel} from "../../vue-app"
    import Wo2Utils from '../../wo2-utils'

    export default {
        name: "uploader",
        props: {
            useVideo: Number,
            params: Object,
            name: String,
            url: Object,
            update: Boolean,
            data: Array
        },
        data() {
            return {
                uploader: Object,
                uuid:Wo2Utils.uuid()
            }
        },
        created() {
            let _this = this;
            Channel.$on('upload', function (params) {
                _this.uploader.upload(params.response).then((results)=>{
                    if(_this.update){
                        let url = _this.url.fetch.replace('{id}',results.data.id);
                        axios.get(`${_this.baseUrl}/${url}`)
                            .then((response)=>{
                                Channel.$emit('updated',{response:response});
                                Channel.$emit('closeModal');
                            })
                    }
                });
            })
        },
        mounted() {
            //Inicializo el WoForm de nuevo para construir el loader de imagenes
            this.init(this.data);
        },
        methods: {
            init(value) {
                if(!wo2_form.hasUploader(this.uuid)){
                    wo2_form.initUploaders('.file-uploader', value, `${location.origin}${this.baseUrl}/${this.url.save}/`,this.uuid);
                    this.uploader = wo2_form.getUploader(this.uuid)[0];
                }
            }
        },
        watch: {
            'data': function (value) {
                wo2_form.removeUploader(this.uuid);
                this.init(value);
            }
        }
    }
</script>

<style scoped>

</style>
