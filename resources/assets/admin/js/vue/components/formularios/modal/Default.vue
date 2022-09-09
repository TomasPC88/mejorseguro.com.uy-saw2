<template>
    <div>
        <div class="field">
            <div class="control has-icons-right">
                <label for="name">Nombre*</label>
                <input v-model="name" id="name" name="name" class="input" required type="text">
                <span class="icon is-small is-right">
                <i class="fa "></i>
            </span>
            </div>
        </div>
        <div class="field">
            <div class="control has-icons-right">
                <label for="description">Descripción</label>
                <input id="description" name="description" class="input" type="text">
                <span class="icon is-small is-right">
                <i class="fa "></i>
            </span>
            </div>
        </div>
    </div>
</template>

<script>
    import {Channel} from "../../../vue-app";

    export default {
        name: "default",
        data(){
            return {
                name:''
            };
        },
        watch:{
            name(newVal){
                if(newVal)
                    Channel.$emit('can',true);
                else
                    Channel.$emit('can',false);
            }
        }
    }
</script>
<style scoped>
    label{
        font-weight: bold;
        text-transform: capitalize;
    }
</style>
