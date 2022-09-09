<template>
    <a :class="{'up':show}" @click="up"><i class="fas fa-angle-up"></i></a>
</template>

<script>
    export default {
        name: "scroll-to",
        props:{
          target:String
        },
        data(){
            return {
                show:false
            }
        },
        mounted(){
          window.addEventListener('scroll',()=>{
              this.show = window.scrollY > window.innerHeight/2;
          })
        },
        methods:{
            up(){
                this.$scrollTo(this.target,800,{
                    easing: 'ease-in',
                    offset: -120,
                    force: true
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    a {
        position: fixed;
        transform:translateY(150px);
        z-index:10000;
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        bottom: 16px;
        right: 16px;
        border-radius: 50%;
        background: #6C98E1;
        transition: ease all .236s;
        cursor: pointer;

        &.up{
            transform: translateY(0);
        }

        @media screen and(max-width: 767px) {
            width: 30px;
            height: 30px;
        }

        &:hover {
            background: darken(#6C98E1, 10%);
            transition: ease all .236s;
        }

        svg {
            font-size: 30px;
            color: #ffff;

            @media screen and(max-width: 767px) {
                font-size: 15px;
            }
        }

    }
</style>