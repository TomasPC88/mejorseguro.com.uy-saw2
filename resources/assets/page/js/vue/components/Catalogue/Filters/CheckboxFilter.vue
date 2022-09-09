<template>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" :name="name" v-model="selected" :id="name">
        <label class="form-check-label" :for="name">{{label|capitalize}}</label>
    </div>
</template>

<script>
    import BaseStore from '../../Base/BaseStore'

    export default {
        name: "checkbox-filter",
        extends:BaseStore,
        props:{
            label:{
                type:String,
                required:true
            }
        }
    }
</script>

<style scoped>

</style>