<template>
    <div :style="css" :class="'alert-'+type" class="text-center">{{message}}</div>
</template>

<script>
    export default {
        name: "alert",
        props:{
            css:Object,
            type:String,
            message:String
        }
    }
</script>

<style scoped>

</style>