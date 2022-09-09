<template>
    <div class="fleet__listing listing">
        <div v-if="isFetching" class="container">
            <div class="container wo-preloader">
                <spinner :css="{height:'500px',width:'100px'}"></spinner>
            </div>
        </div>
        <template v-else>
            <div v-if="items.length > 0" class="row">
                <item :show-price="isFiltered" :url="href" v-for="item of items" :key="item.id" :item="item"/>
                <paginator class="paginator" :current-page="currentPage" :total-pages="totalPages" @filter="paginate" target=".fleet__listing"/>
            </div>
            <div v-else>
                <h3 class="w-100 text-primary text-center">{{$t('cero_producto')}}</h3>
            </div>
        </template>
    </div>
</template>

<script>
    import BaseCatalogue from '../Base/BaseCatalogue'
    import Paginator from "../Paginator/Paginator";
    import Spinner from "../Spinner";
    import Item from "../Item/Item";

    export default {
        name: 'catalogue',
        extends: BaseCatalogue,
        components: {
            Paginator,
            Spinner,
            Item,
        },
        props:{
          isFiltered:{
              type:Boolean,
              default:false
          }
        },
        created() {
            if(!this.isFiltered){
                this.fetch()
                return
            }
            this.paginate(1)
        }
    };
</script>

<style lang="css" scoped>
    .wo-preloader {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 50vh;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.5s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    .paginator{
        width:50%;
        margin:0 auto;
    }
</style>
