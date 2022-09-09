<script>
    import BaseListenOnStore from '../Base/BaseListenOnStore'
    import {Channel} from '../../vue-app'

    // TODO Hacer el guardado de los elementos del fetch en el store para luego matchear los formularios en el caso del select multiple
    export default {
        name: "base-select",
        extends: BaseListenOnStore,
        props: {
            placeholder: {
                type: String,
                default: 'seleccione'
            },
            label: {
                type: String,
                default: 'name'
            },
            code: {
                type: String,
                default: 'id'
            },
            disabled:{
              type:Boolean,
              default:false
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

            this.$on('fetched',()=>{
                this.$emit('clear-selection')
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
        watch:{
            check(v){
                if(!v)
                    this.clearSelected()
            }
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