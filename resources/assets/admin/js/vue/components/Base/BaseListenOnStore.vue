<script>
    import BaseStore from './BaseStore'

    export default {
        name: "base-listen-on-store",
        extends: BaseStore,
        props: {
            related: {
                type: Boolean,
                default: false
            },
            listen: [Array, String]
        },
        methods: {
            fetchFromStore(k) {
                return this.$store.getters.getSelectedByName(k)
            }
        },
        computed: {
            params() {
                if (this.related) {
                    if (Array.isArray(this.listen))
                        return this.listen.reduce((acc, l) => (acc[l] = this.fetchFromStore(l), acc), {});

                    return {
                        [this.listen]: this.fetchFromStore(this.listen)
                    }
                }

                return {}
            },
            check() {
                //Is neccesary to have a getter that matches storeKey value to access the different attributes selected
                if (this.related) {
                    const isNotEmpty = (v) => {
                        return v ? true : false
                    };

                    if (Array.isArray(this.listen))
                        return this.listen.every(l => isNotEmpty(this.fetchFromStore(l)) === true);

                    return isNotEmpty(this.fetchFromStore(this.listen))
                }
                return true
            }
        }
    }
</script>

<style scoped>

</style>