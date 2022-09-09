<template>
    <form ref="form" @submit.prevent="submit" class="vee" novalidate>
        <vue-recaptcha v-if="useRecaptcha"
                       ref="recaptcha"
                       @verify="send"
                       @expired="resetRecaptcha"
                       size="invisible"
                       :sitekey="recaptchaKey">
        </vue-recaptcha>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-3 col-xl-3">
                <label for="name">{{ $t('form.placeholder.nombre') }}</label>
                <input id="name" v-validate="'required'" class="form-control"
                       required type="text" name="nombre" value="">
                <span class="error">{{errors.first('nombre')}}</span>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-3 col-xl-3">
                <label for="lastname">{{ $t('form.placeholder.apellido') }}</label>
                <input id="lastname" v-validate="'required'" class="form-control"
                       required type="text" name="apellido" value="">
                <span class="error">{{errors.first('apellido')}}</span>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-3 col-xl-3">
                <label for="pais">{{ $t('form.placeholder.pais') }}</label>
                <select-simple
                        id="pais"
                        name="pais_id"
                        :url="{
                                fetch:routes.paises
                              }"
                />
                <span class="error">{{errors.first('pais_id')}}</span>
            </div>

            <div class="form-group col-12 col-md-6 col-lg-3 col-xl-3">
                <label for="ciudad">{{ $t('form.placeholder.ciudad') }}</label>
                <select-simple
                        id="ciudad"
                        name="ciudad_id"
                        :related="true"
                        listen="pais_id"
                        :url="{
                                fetch:routes.ciudades
                               }"
                />
                <span class="error">{{errors.first('ciudad_id')}}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-12 col-md-6 col-lg-3 col-xl-3">
                <label for="tel">{{ $t('form.placeholder.telefono') }}</label>
                <input id="tel" v-validate="'required'" class="form-control"
                       required type="text" name="telefono" value="">
                <span class="error">{{errors.first('telefono')}}</span>
            </div>
            <div class="form-group col-12 col-md-6 col-lg-9 col-xl-9">
                <label for="email">{{ $t('form.placeholder.email') }}</label>
                <input id="email" v-validate="'required|email'" class="form-control"
                       type="text" required name="email" value="">
                <span class="error">{{errors.first('email')}}</span>
            </div>
        </div>
        <div class="form-group">
            <label for="query">{{ $t('form.placeholder.feedback') }}</label>
            <textarea id="query" class="form-control" v-validate="'required'" name="consulta" rows="3"></textarea>
            <span class="error">{{errors.first('consulta')}}</span>
        </div>
        <div class="row">
            <alert v-if="alert.active" ref="alert" :type="alert.type" :message="alert.text" class="alert w-100"></alert>
            <div class="col-12">
                <button type="submit" :class="{disabled:!can || isLoading}" class="custom-btn">
                    <spinner v-if="isLoading" :css="{height:'40px'}"></spinner>
                    <span v-else>{{ $t('form.label.reservar') }}</span>
                </button>
            </div>
        </div>
    </form>

</template>

<script>
    import SelectSimple from '../Catalogue/Filters/SelectSimpleFilter'
    import Spinner from '../Spinner'
    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'

    export default {
        name: "personal-data",
        extends: BaseForm,
        components: {
            SelectSimple,
            Spinner,
            Alert
        },
        props: {
            routes: {
                type: Object,
                required: true
            },
            can: {
                type: Boolean,
                default: false
            }
        },
        created() {
            this.$on('sent', () => {
                this.$store.commit('removeSelectedByName','ciudad_id');
                this.$store.commit('removeSelectedByName','pais_id');
            })
        },
        methods: {
            submit() {
                const params = Object.assign({}, this.$store.getters.getAllSelected());
                params.fecha_entrega = `${this.$store.getters.getSelectedByName('fecha_entrega')} ${this.$store.getters.getSelectedByName('hora_entrega')}`;
                params.fecha_devolucion = `${this.$store.getters.getSelectedByName('fecha_devolucion')} ${this.$store.getters.getSelectedByName('hora_devolucion')}`;
                params.settlement = this.$store.getters.getSettlement()
                this.go(params)
            }
        }
    }
</script>