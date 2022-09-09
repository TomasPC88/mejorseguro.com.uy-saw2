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
                    <component ref="component" v-if="form" :data="form.data" :is="form.name"></component>
                    <custom v-for="(field,index) of fields" :key="index" :data="field"></custom>
                </section>
                <m-footer @close="close" @send="send" :can="can" :actions="actions"></m-footer>
            </form>
        </div>
    </div>
</template>

<script>
    import {Channel} from "../../vue-app";
    import Custom from '../Custom'
    import Footer from './Footer'
    import Default from '../formularios/modal/Default'

    let data = {
        active: false,
        can: false,
        url: '',
        delUrl: '',
        title: '',
        formStack: [],
        from: 0,
        fields: [],
        useEvent: false
    };
    export default {
        name: "modal",
        components: {
            custom: Custom,
            default: Default,
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
        },
        data() {
            return data;
        },
        created() {
            this.url = this.propURL;
            this.delUrl = this.delPropUrl;
            this.title = this.propTitle;
            this.fields = this.propFields;
            this.actions = this.propActions;

            Channel.$on('openModal', (params) => {
                this.url = params.url;
                this.delUrl = params.delUrl;
                this.title = params.title;
                this.from = params.from;
                this.form = params.form;
                this.fields = params.fields;
                this.actions = params.actions;
                this.related = params.related;
                this.active = true;
            });

            Channel.$on('closeModal', () => {
                this.close();
            });

            Channel.$on('can', (is) => {
                this.can = is;
            });
        },
        methods: {
            close() {
                this.formStack.pop();
            },
            send(url) {
                Channel.$emit('sending', true);
                axios.post(url, Object.assign(Object.fromEntries(new FormData(this.$refs.form)), this.related || {}))
                    .then((response) => {
                        Channel.$emit(response.data.action || 'saved', {from: this.from, response: response});
                        if (this.form.hasUploader && response.data.action !== 'deleted') {
                            this.useEvent = true;
                            Channel.$emit('upload', {from: this.from, response: response});
                        }

                        this.toast('success', response.data.message || 'Nuevo elemento creado');
                    })
                    .catch((e) => {
                        console.log(e);
                        this.toast('error', 'Ocurrió un error en la transacción');
                    })
                    .finally(() => {
                        Channel.$emit('sending', false);
                        this.$refs.form.reset();
                    })
            }
        },
        computed: {
            form: {
                set(v) {
                    this.formStack.push(v)
                },
                get() {
                    return this.formStack[this.formStack.length - 1]
                }
            }
        },
        watch: {
            formStack(newVal) {
                if (!newVal.length && !this.useEvent) {
                    this.active = false;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .is-disabled {
        pointer-events: none;
        background: lighten(gray, 20%);
    }

    .modal {
        z-index: 21;
    }

    .modal-card {
        width: 700px;
    }

    #modalForm {
        .modal-card-body {
            padding: 20px 40px 35px;
            max-height: 420px;
        }
        .modal-card-foot {
            .is-danger {
                position: absolute;
                right: 10px;
            }
        }
    }
</style>
