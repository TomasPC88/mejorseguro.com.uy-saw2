<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container position-relative">
            <a class="navbar-brand" href="/">
                <img class="img-fluid" :src="asset('/img/logo.png')" alt="" srcset="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
                    aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li v-for="(route,index) of routes" :key="index" class="nav-item" :class="{'active':isActive(route.url)}">
                        <a class="nav-link" :href="route.url">{{$t(route.label)|capitalize}}</a>
                    </li>
                </ul>
            </div>
            <slot name="links"></slot>
            <lang-selector :langs="[{es:'ES'},{uk:'UK'},{pt:'PT'}]"></lang-selector>
        </div>
    </nav>
</template>

<script>
    import LangSelector from './Selectors/LangSelector'

    export default {
        name: "nav-bar",
        components:{
          LangSelector
        },
        props:{
          routes:{
              type:Array,
              required:true
          }
        },
        methods:{
          isActive(v){
              return v === location.href.replace(/\/$/, "");
          }
        }
    }
</script>

<style scoped>

</style>