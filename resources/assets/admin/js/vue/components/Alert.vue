<template>
    <div v-if="!hidden" :class="type" class="notification">
        <button class="delete" @click.prevent="hidden=true"></button>
        {{message}}
    </div>
</template>

<script>
    export default {
        name: "alert",
        props:{
            type:String,
            message:String
        },
        data(){
            return {
                hidden:false
            }
        }
    }
</script>

<style scoped>

</style>