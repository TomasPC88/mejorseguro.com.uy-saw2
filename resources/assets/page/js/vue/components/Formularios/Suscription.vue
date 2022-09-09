<template>
   <div class="suscription">
       <h4>{{ $t('form.label.label_subscription') }}</h4>
       <alert v-if="alert.active" ref="alert" :type="alert.type" :message="alert.text" class="alert"></alert>
       <form class="vee" ref="form" novalidate>
           <vue-recaptcha v-if="useRecaptcha"
                          ref="recaptcha"
                          @verify="send"
                          @expired="resetRecaptcha"
                          size="invisible"
                          :sitekey="recaptchaKey">
           </vue-recaptcha>
           <div class="input-group">
               <input v-validate="'required|email'" class="form-control" type="text" required name="email"
                      :placeholder="$t('form.placeholder.subscription')" aria-label="Recipient's username" aria-describedby="basic-addon2">
               <span class="errors">{{errors.first('email')}}</span>
               <div class="input-group-append">
                   <spinner v-if="isLoading" :css="{height:'40px'}"></spinner>
                   <span v-else class="input-group-text" @click.prevent="go" id="suscribe-btn"><i class="fas fa-paper-plane"></i></span>
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
        extends: BaseForm,
        name: "suscription",
        components:{
            alert:Alert,
            spinner:Spinner
        }
    }
</script>

<style scoped>
    span.errors{
        position: absolute;
        top:50px;
        left:10%;
        color:white;
    }
</style>
