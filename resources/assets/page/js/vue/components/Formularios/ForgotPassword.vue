<template>
    <div style="width: 60%">
        <form @submit.prevent="go" ref="form" class="vee" novalidate>
            <vue-recaptcha v-if="useRecaptcha"
                           ref="recaptcha"
                           @verify="send"
                           @expired="resetRecaptcha"
                           size="invisible"
                           :sitekey="recaptchaKey">
            </vue-recaptcha>
            <div class="form-row">
                <alert v-if="alert.active" ref="alert" :css="{'text-align':'center'}" :type="alert.type"
                       :message="alert.text" class="alert"></alert>
            </div>
            <div class="form-row">
                <div class="col-12 mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" v-validate="'required|email'" autofocus
                               placeholder="Ingrese una dirección de correo*"
                               name="email"
                               required>
                        <span>{{errors.first('email')}}</span>
                    </div>
                </div>
            </div>
            <div class="row btn-row">
                <spinner v-if="sending" :css="{margin:'10px 50px',height:'50px'}"></spinner>

                <button v-else class="float-right btn btn-primary" type="submit">Enviar</button>
            </div>
        </form>
    </div>
</template>

<script>
    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'
    import Spinner from '../Spinner'

    export default {
        name: "forgot-password",
        extends: BaseForm,
        props: {
            forgotUrl: String
        },
        data() {
            return {
                user: {}
            }
        },
        components: {
            alert: Alert,
            spinner: Spinner
        }
    }
</script>

<style scoped>
    input[type='email'] {
        width: inherit !important;
    }
    .alert{
        width:100%;
    }
</style>