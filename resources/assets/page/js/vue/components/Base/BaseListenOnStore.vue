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
            listen: [Array, String],
            shared_store: {
                type: Object,
                required: false,
                default: function () {
                    return {
                        is: false,
                        where: 'shared',
                        substract: false
                    }
                },
                validator: function (obj) {
                    return Object.keys(obj).every(k => ['is', 'where','substract'].indexOf(k) !== -1)
                }
            },
            url: {
                type: Object,
                default: function () {
                    return {
                        fetch: '',
                        save: ''
                    }
                }
            },
        },
        methods: {
            fetchFromStore(k) {
                return this.$store.getters.getSelectedByName(k)
            },
            fetch(force = false) {
                if ((force || !this.items.length > 0) && this.check) {
                    this.$store.commit('setLoading', {
                        is: true,
                        uid: this._uid
                    });
                    axios.get(this.url.fetch, {params: this.params})
                        .then((r) => {
                            this.items = r.data.data;
                            this.$emit('fetch');
                        }).then(() => {
                    }).catch((e) => {
                        console.error(e);
                        this.toast('error', 'Ocurrió un error en la transacción');
                    })
                        .finally(() => {
                            this.$store.commit('setLoading', {
                                is: false,
                                uid: this._uid
                            });
                        });
                }
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
            },
            items: {
                set(v) {
                    this.$store.commit('putInItems', {
                        key: this.shared_store.is
                            ? this.shared_store.where
                            : this.name,
                        value: v
                    })
                },
                get() {
                    const where = this.shared_store.is
                        ? this.shared_store.where
                        : this.name;

                    return this.$store.getters.getItemsByName(where)
                }
            }
        }
    }
</script>