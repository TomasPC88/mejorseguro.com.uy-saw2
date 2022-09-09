<template>
    <div>
        <alert v-if="alert.active" ref="alert" :css="{'text-align':'center'}" :type="alert.type" :message="alert.text"
               class="alert"></alert>
        <form ref="form" class="vee" novalidate @submit.prevent="go">
            <input type="hidden" name="user_id" :value="user_id">
            <vue-recaptcha v-if="useRecaptcha"
                           ref="recaptcha"
                           @verify="send"
                           @expired="resetRecaptcha"
                           size="invisible"
                           :sitekey="recaptchaKey">
            </vue-recaptcha>
            <div class="form-row">
                <h3 class="text-center w-100">E-mail</h3>
            </div>
            <div class="form-row">
                <div class="col-12 mb-3">
                    <div class="input-group my-3">
                        <input type="password" name="password"
                               v-validate="'required|min:8'" ref="password" class="form-control" placeholder="Contraseña*"
                               required>
                        <span>{{errors.first('password')}}</span>
                    </div>
                    <div class="input-group my-3">
                        <input type="password" name="confirm"
                               v-validate="'required|confirmed:password'" class="form-control" placeholder="Confirmar constraseña*"
                               required>
                        <span>{{errors.first('confirm')}}</span>
                    </div>
                </div>
            </div>
            <div class="row btn-row">
                <spinner v-if="sending" :css="{margin:'10px 50px',height:'50px'}"></spinner>

                <button v-else class="float-right btn btn-primary" type="submit">Reestablecer</button>
            </div>
        </form>
    </div>
</template>

<script>
    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'
    import Spinner from '../Spinner'

    export default {
        name: "reestablish-password",
        extends: BaseForm,
        props: {
            user_id: Number
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
    input{
        width: inherit!important;
    }
</style>