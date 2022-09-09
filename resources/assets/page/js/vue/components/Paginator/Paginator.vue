<template>
    <div v-if="totalPages > 1" class="paginador-box py-4">
        <nav>
            <ul class="pagination">
                <li class="page-item">
                    <a
                            class="page-link"
                            href="#"
                            tabindex="-1"
                            v-if="currentPage != 1"
                            @click.self.prevent="$emit('filter',currentPage + 1)"
                    >{{$t('form.label.anterior')}}</a>
                </li>
                <li class="page-item" :class="{active:currentPage === v}" v-for="(v,index) in pages" :key="index">
                    <a
                            class="page-link"
                            href="#"
                            @click.self.prevent="$emit('filter',isNaN(v)?currentPage + 3:v)"
                    >{{ v }}</a>
                </li>
                <li class="page-item">
                    <a
                            class="page-link"
                            href="#"
                            v-if="currentPage < totalPages"
                            @click.self.prevent="$emit('filter',currentPage + 1)"
                    >{{$t('form.label.siguiente')}}</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import {Channel} from "../../vue-app";

    export default {
        name: "paginator",
        props: {
            totalPages: {
                type:Number,
                required:true
            },
            target: {
                type: String,
                required: true
            },
            currentPage:{
                type:Number,
                default:1
            }
        },
        data() {
            return {
                pages: [],
            }
        },
        created(){
            this.$watch(vm => [vm['totalPages'], vm['currentPage']], val => {
                this.init();
            });
        },
        mounted() {
            this.init();
        },
        methods: {
            init(){
                this.pageNumbers(this.totalPages, this.currentPage);
            },
            pageNumbers(count, current) {
                const shownPages = 3;
                this.pages = [];
                if (current > count - shownPages) {
                    if (count === 2) this.pages.push(count - 1, count);
                    else this.pages.push(count - 2, count - 1, count);
                } else {
                    this.pages.push(current, current + 1, current + 2, "...", count);
                }
            }
        },
        watch: {
            currentPage() {
                const self = this;
                this.$scrollTo(this.target, 800, {
                    easing: 'ease-in',
                    offset: -200,
                    force: true,
                    onDone(){
                        // self.init();
                    }
                });

            }
        }
    };
</script>
<style scoped>
    ul.pagination{
        margin: auto;
        width: fit-content;
    }
</style>