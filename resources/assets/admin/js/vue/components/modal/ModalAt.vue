<template>
    <div :id="id" class="modal" :class="{'is-active':active}">
        <div class="modal-background"></div>
        <div class="modal-card">
            <form id="modalForm" ref="form" novalidate>
                <header class="modal-card-head">
                    <p class="modal-card-title">{{title}}</p>
                    <button @click.prevent="close()" class="delete" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <component :data="form.data" :is="form.name"></component>
                    <custom v-for="(field,index) of fields" :key="index" :data="field"></custom>
                </section>
                <m-footer @close="close" @send="send" :can="true" :actions="actions" :crear="true"></m-footer>
            </form>
        </div>
    </div>
</template>

<script>
    import {Channel} from "../../vue-app";
    import Custom from '../Custom'
    import Footer from './Footer'
    import Default from '../formularios/modal/Default'
    import Version from '../formularios/modal/Version'

    let data = {
        active: false,
        can: false,
        url: '',
        delUrl: '',
        title: '',
        form: {},
        from: 0,
        fields: [],
    };
    export default {
        name: "modalat",
        components: {
            custom: Custom,
            default: Default,
            version: Version,
            mFooter: Footer
        },
        props: {
            id: String,
            propTitle: String,
            propURL: String,
            delPropUrl: String,
            propForm: Object,
            propFields: Array,
            propActions: Array,
            // TODO Provisional hasta investigar por que cuando hago un reset del form, los bindings de vue contra value no funcionan
            propFormReset: Boolean
        },
        data() {
            return data;
        },
        created() {
            this.url = this.propURL;
            this.delUrl = this.delPropUrl;
            this.title = this.propTitle;
            this.form = this.propForm;
            this.fields = this.propFields;
            this.actions = this.propActions;
            this.useFormReset = this.propFormReset;

            Channel.$on('openAt', (params) => {
                this.url = params.url;
                this.delUrl = params.delUrl;
                this.title = params.title;
                this.from = params.from;
                this.form = params.form;
                this.fields = params.fields;
                this.actions = params.actions;
                this.active = true;
            });

            Channel.$on('closeModal', (params) => {
                this.close();
            });

            Channel.$on('can', (is) => {
                this.can = is;
            });
        },
        methods: {
            close() {
                this.active = false;
                if (this.useFormReset)
                    this.$refs.form.reset();
            },
            send(url) {
                let _this = this;
                let useEvent= false;
                Channel.$emit('sending', true);
                axios.post(url, new FormData(this.$refs.form))
                    .then((response) => {
                        Channel.$emit(response.data.action || 'saved', {from: _this.from, response: response});
                        if (_this.form.hasUploader && response.data.action !== 'deleted') {
                            useEvent = true;
                            Channel.$emit('upload', {from: _this.from, response: response});
                        }

                        _this.toast('success', response.data.message || 'Nuevo elemento creado');
                    })
                    .catch((e) => {
                        console.log(e);
                        _this.toast('error', 'Ocurrió un error en la transacción');
                    })
                    .finally(() => {
                        Channel.$emit('sending', false);
                        if (!useEvent)
                            _this.close();
                    })
            }
        }
    }
</script>

<style lang="scss">
    .is-disabled {
        pointer-events: none;
        background: lighten(gray, 20%);
    }

    .modal-card {
        width: 700px;
    }

    #modalFormAt {
        .modal-card-body {
            padding: 35px 40px 35px;
            max-height: 420px;
        }

        .component {
            position: relative;
            margin-top: 5px;
            a {
                position: absolute;
                top: -42px;
                right: 0;
            }
        }

        .modal-card-foot {
            .is-danger {
                position: absolute;
                right: 10px;
            }
            .foot-btn{
                width: 100%;
            }
        }
    }
</style>