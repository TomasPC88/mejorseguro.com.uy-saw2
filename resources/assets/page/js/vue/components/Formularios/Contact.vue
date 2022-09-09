<template>
    <div class="formulario">
        <alert v-if="alert.active" ref="alert" :type="alert.type" :message="alert.text" class="alert"></alert>
        <form ref="form" @submit.prevent="go" class="vee" novalidate>
            <vue-recaptcha v-if="useRecaptcha"
                           ref="recaptcha"
                           @verify="send"
                           @expired="resetRecaptcha"
                           size="invisible"
                           :sitekey="recaptchaKey">
            </vue-recaptcha>
            <div class="form-row">
                <div class="form-group col-12 col-md-12 col-lg-6">
                    <label for="">Nombre</label>
                    <input id="nombre" v-validate="'required'" class="form-control"
                           required type="text" name="nombre" value="" :placeholder="$t('form.placeholder.nombre')">
                    <span class="error">{{errors.first('nombre')}}</span>
                </div>
                <div class="form-group col-12 col-md-12 col-lg-6">
                    <label for="">Correo</label>
                    <input id="email" v-validate="'required|email'" class="form-control"
                           type="text" required name="email" value="" :placeholder="$t('form.placeholder.email')">
                    <span class="error">{{errors.first('email')}}</span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="">Teléfono</label>
                    <input id="tel" v-validate="'required'" class="form-control"
                           required type="text" name="telefono" value="" :placeholder="$t('form.placeholder.telefono')">
                    <span class="error">{{errors.first('telefono')}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="">Mensaje</label>
                <textarea class="form-control" v-validate="'required'" :placeholder="$t('form.placeholder.query')" id="consulta" name="consulta" rows="3"></textarea>
                <span class="error">{{errors.first('consulta')}}</span>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" :class="{disabled:isLoading}" class="custom-btn">
                        <spinner v-if="isLoading" :css="{height:'40px'}"></spinner>
                        <span v-else>{{ $t('form.placeholder.enviar') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'
    import Spinner from '../Spinner'

    export default {
        extends:BaseForm,
        name: "contact",
        components:{
            alert:Alert,
            spinner:Spinner
        },
        watch:{
            sending(newVal){
                if(!newVal)
                    this.$scrollTo('.alert',500,{
                        easing: 'linear',
                        offset: -150,
                        force: true
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .formulario {
        width: 100%;

        form {

            .form-group {
                margin-bottom: 0rem;

                span {
                    display: block;
                    height: 17px;
                }
            }

            button {
                margin-top: 10px;
            }
        }

    }

</style>
