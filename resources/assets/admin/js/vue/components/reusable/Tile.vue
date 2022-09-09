<template>
    <div class="container">
        <img :src="src" alt="">
    </div>
</template>

<script>
    export default {
        name: "tile",
        props: {
            src: String
        }
    }
</script>

<style lang="scss" scoped>
    .container {
        width: auto;
        height: auto;
        cursor: pointer;
        background-color: #e2e2e2;
        img {
            border: solid 2px darken(white, 10%);
            width: 100%;
            height: 100%;
            object-fit: scale-down;
            &:hover {
                border-color: darken(white, 30%);
                transition: border-color ease .3s;
            }
        }
    }
</style>
