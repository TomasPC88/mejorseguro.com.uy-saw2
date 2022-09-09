<script>
    import BaseListenOnStore from '../Base/BaseListenOnStore'
    import {Channel} from '../../vue-app'

    // TODO Hacer el guardado de los elementos del fetch en el store para luego matchear los formularios en el caso del select multiple
    export default {
        name: "base-select",
        extends: BaseListenOnStore,
        props: {
            label: {
                type: String,
                default: 'name'
            },
            code: {
                type: String,
                default: 'id'
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
            disabled: {
                type: Boolean,
                default: false
            },
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
            add: Boolean,
            modalForm: Object,
            title: String,
            rules: String
        },
        data() {
            return {
                relatedItem: {}
            }
        },
        created() {
            Channel.$on('saved', (params) => {
                if (params.from === this._uid)
                    this.items.push(params.response.data.data);

            });

            this.fetch();

            if (this.related)
                this.$store.subscribe(({type, payload}) => {
                    if (type === 'putInSelected') {
                        let passes = false;
                        if (Array.isArray(this.listen))
                            passes = this.listen.indexOf(payload.key) !== -1;
                        else
                            passes = this.listen == payload.key;

                        if (passes)
                            this.fetch(true)
                    }
                });

        },
        methods: {
            fetch(force = false) {
                if ((force || !this.items.length > 0) && this.check) {
                    this.$store.commit('setLoading', {
                        is: true,
                        uid: this._uid
                    });
                    axios.get(`${this.baseUrl}/${this.url.fetch}`, {params: this.params})
                        .then((r) => {
                            this.items = r.data.data;
                            if (this.related)
                                this.$emit('clear-selection');
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
            },
            openModal() {
                this.modalForm.data = {
                    item: {},
                    related: this.relatedItem
                };
                let params = {
                    title: !this.modalForm.title ? `Nuevo ${this.title}` : `${this.modalForm.title}`,
                    form: this.modalForm,
                    from: this._uid,
                    actions: [
                        {
                            name: 'send',
                            title: 'Guardar',
                            url: `${this.baseUrl}/${this.url.save}`,
                            classNames: 'is-success',
                            icon: 'fa-check',
                            event: 'can'
                        }
                    ]
                };
                if (this.related)
                    params.related = this.params;


                Channel.$emit('openModal', params);
            }
        },
        computed: {
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
            },
            sharedSelectedStack:{
                set(v){
                    this.$store.commit('putInSelected', {
                        key:this.shared_store.where,
                        value: v
                    })
                },
                get(){
                    return this.$store.getters.getSelectedByName(this.shared_store.where)
                }
            }
        },
        watch: {
            check(v) {
                if (!v)
                    this.clearSelected()
            },
            /*selected(v){
                if(this.shared_store.is)
                    this.sharedSelectedStack = this.sharedSelectedStack.filter()
            }*/
        }
    }
</script>
<style lang="scss">
    .component {
        position: relative;

        a {
            position: absolute;
            right: 0;
            top: -10px;
        }
    }
</style>