<script>
    import {Channel} from "../../vue-app"

    export default {
        name: 'base-catalogue',
        props: {
            url: {
                type: String,
                required: true
            },
            name: {
                type: String,
                default: 'products'
            },
            itemsPerPage: {
                type: Number,
                default: 8
            },
            href: {
                type: String,
                required: true
            },
        },
        data() {
            return {
                pagination: {}
            }
        },
        created(){
            this.$store.subscribe(({type, payload}) => {
                if (type === 'putInItems') {
                    if(payload.key === this.name)
                        this.paginate()
                }
            });

            Channel.$on('paginate',(v)=>{
                this.paginate(1,this.itemsPerPage,v)
            })
        },
        methods: {
            fetch() {
                this.$store.dispatch('fetch', {name: this.name, url: this.url})
            },
            paginate(currentPage = 1, itemsPorPage = this.itemsPerPage, items = this.inStore) {
                const from = (currentPage * itemsPorPage) - itemsPorPage;
                const to = currentPage * itemsPorPage;
                this.pagination = {
                    total: items.length,
                    current_page: currentPage,
                    from: currentPage,
                    last_page: Math.ceil(items.length / itemsPorPage),
                    data: items.slice(from, to)
                }
            }
        },
        computed: {
            total() {
                return this.pagination.total
            },
            totalPages() {
                return this.pagination.last_page;
            },
            isFetching() {
                return this.$store.getters.isFetching
            },
            currentPage() {
                return this.pagination.current_page || 1
            },
            items() {
                return this.pagination.data || []
            },
            inStore(){
                return this.$store.getters.getItemsByName(this.name)
            }
        }
    }
</script>
<style scoped>

</style>