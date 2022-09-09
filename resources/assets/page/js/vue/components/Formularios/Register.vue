<template>
    <form ref="form" @submit.prevent="go" novalidate class="vee form-horizontal">
        <alert v-if="alert.active" ref="alert" :type="alert.type" :css="{'text-align':'center'}"
               :message="alert.text" class="alert"></alert>
        <vue-recaptcha v-if="useRecaptcha"
                       ref="recaptcha"
                       @verify="send"
                       @expired="resetRecaptcha"
                       size="invisible"
                       :sitekey="recaptchaKey">
        </vue-recaptcha>
        <div class="form-group">
            <label class="col-md-4 control-label">Email</label>
            <div class="col">
                <input id="register-email" type="email" class="form-control" v-validate="'required|email'"
                       name="email">
                <span>{{errors.first('email')}}</span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-5 control-label">Contraseña</label>
            <div class="col">
                <input ref="password" id="password" type="password" class="form-control" v-validate="'required'"
                       name="password">
                <span>{{errors.first('password')}}</span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-12 control-label">Confirmar Contraseña</label>
            <div class="col">
                <input id="confirm" type="password" class="form-control" v-validate="'required|confirmed:password'"
                       name="confirm">
                <span>{{errors.first('confirm')}}</span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <spinner v-if="isLoading" :css="{height:'50px',float:'right'}"></spinner>
                <button v-else type="submit" class="btn btn-primary mb-4 submit">
                    <div class="d-flex align-items-center justify-content-center">
                        Registrarme
                        <span class="wo-loading-contact-form spinner-border spinner-border-sm ml-2 d-none"
                              role="status" aria-hidden="true"></span>
                    </div>
                </button>
            </div>
        </div>
    </form>
</template>
<script>
    import BaseForm from '../Base/BaseForm'
    import Alert from '../Alert'
    import Spinner from '../Spinner'
    import {Channel} from '../../vue-app'

    export default{
        name: 'register',
        extends: BaseForm,
        components: {
            Spinner, Alert
        },
        props: {
            forgotUrl: String,
            registerUrl: String,
            previousUrl: String
        },
        methods:{
            login(){
                this.go().then((result)=>{
                    if(result)
                        this.$store.commit('setUser',result)
                })
            }
        }
    }
</script>
<style scoped>
    .form-group span {
        font-size: 12px;
        color: red;
    }
</style>