<script>
    export default {
        name: "base-sync",
        props: {
            url: Object,
            state: String,
            path: String,
            related: Boolean,
            keys: {
                type: Object,
                required: false,
                default: () => {
                    return {
                        value: 'id',
                        label: 'name'
                    }
                }
            }
        },
        data() {
            return {
                changes: window.changes,
                relatedItem: Object
            }
        },
        methods: {
            sync(url) {
                if (this.changes && Object.keys(this.changes).length > 0) {
                    if (Object.keys(this.changes).indexOf(this.state) !== -1) {
                        return this.fetch(url, (this.related && this.relatedItem && this.relatedItem.hasOwnProperty(this.keys.value)));
                    }
                }

                if (!this.store.fetched)
                    return this.fetch(url);

                else
                    return new Promise((resolve, reject) => {
                        resolve('done');
                    });
            },
            fetch(url, related = false) {
                url = url ? url : this.url.fetch;
                return axios.get(`${this.baseUrl}/${url}`)
                    .then((r) => {
                        this.store = r.data.data.map(item => this.getData(item));
                        if (this.changes) {
                            let params = {};
                            if (related && (this.changes[this.state].indexOf(this.relatedItem[this.keys.value] + '') !== -1))
                                params = {
                                    id: this.relatedItem[this.keys.value]
                                };

                            axios.delete(`${this.baseUrl}/action/delete/${this.state}`, {
                                params: params
                            }).then(() => {
                                this.changes = undefined;
                            });
                        }
                    })
                    .catch((e) => {
                        this.toast('error', 'Ocurrió un error en la transacción');
                        console.log(e);
                    })
            },
            getData(obj) {
                if (this.path)
                    return eval(`obj.${this.path}`);
                return obj;
            }
        },
        computed: {
            store: {
                set(v) {
                    if (this.related && this.relatedItem)
                        this.$store.dispatch('saveItemsRelated', {
                            section: this.state,
                            related: this.relatedItem[this.keys.value],
                            items: v
                        });
                    else
                        this.$store.dispatch('saveItem', {section: this.state, item: v});
                },
                get() {
                    if (this.related && this.relatedItem)
                        return this.$store.getters.getItemsRelated(this.state, this.relatedItem[this.keys.value]);

                    return this.$store.getters.getItem(this.state);
                }
            }
        }
    }
</script>