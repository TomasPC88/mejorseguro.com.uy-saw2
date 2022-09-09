import Toasted from "vue-toasted"
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import VueScrollTo from 'vue-scrollto'
import * as Lang from './lang/messages'
import Vuex from 'vuex'
import Store from './store/index'
import VueI18n from 'vue-i18n'


//Vue app
Vue.use(Vuex);
Vue.use(VueI18n);
Vue.use(Toasted);
Vue.use(VueScrollTo);

const store = new Vuex.Store(Store);

const i18n = new VueI18n({
    locale: 'en', // set locale
    messages: Lang.messages, // set locale messages
    silentTranslationWarn: true
});


Vue.use(VeeValidate, {
    i18nRootKey: 'validaciones', // customize the root path for validation messages.
    i18n
    // dictionary: {
    //     en: validationMessages
    // }
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('LangSelector', require('./components/Selectors/LangSelector'));
Vue.component('Contact', require('./components/Formularios/Contact'));
Vue.component('ForgotPassword', require('./components/Formularios/ForgotPassword'));
Vue.component('ReestablishPassword', require('./components/Formularios/ReestablishPassword'));
Vue.component('Login', require('./components/Formularios/Login'));
Vue.component('Register', require('./components/Formularios/Register'));
Vue.component('Suscription', require('./components/Formularios/Suscription'));
Vue.component('Profile', require('./components/Formularios/Profile'));

export const Channel = new Vue();

Vue.mixin({
    data() {
        return {
            locale: window.locale
        }
    },
    methods: {
        toast(type, text, duration = 1500) {
            eval(`this.$toasted.${type}('${text}', {
                theme: 'outline',
                duration:${duration}
        })`);
        },
        asset(path) {
            return '/page_assets/' + path
        },
        storage(path) {
            return '/storage/' + path
        },
        parseResponse({message, status}) {
            switch (status) {
                case 'ERROR':
                    this.toast('error', message);
                    break;
                case 'WARNING':
                    this.toast('warning', message);
                    break;
            }
        },
        clearStorage() {
            sessionStorage.removeItem('vuex')
        }
    },
    filters: {
        pluralize: function (vowl = false, value) {
            return vowl ? `${value}s` : `${value}es`;
        },
        truncate: function (text, length) {
            if (text)
                return text.split(' ', length).join(' ') + '...';
        },
        dateFormat(date, options = null) {
            if (!options)
                options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};

            return new Date(date).toLocaleString('es-ES', options);
        },
        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        priceFormat: function (price) {
            return new Intl.NumberFormat('de-DE').format(price);
        },
        isNotEmpty(v) {
            if (Array.isArray(v))
                return v.length > 0

            if (v === Object(v))
                return Object.keys(v).length > 0

            return v !== null || v !== '' || v !== 0
        },
        withDefault(value) {
            switch (typeof value) {
                case 'Object':
                    return Array.isArray(value) ? [] : {}
                case 'String':
                    return ''
                case 'Number':
                    return 0
            }
        }
    },
    computed: {
        isLoading() {
            return this.$store.getters.isLoading(this)
        }
    }
});

const app = new Vue({
    el: '#vue',
    i18n,
    store,
    mounted() {
        i18n.locale = this.locale;
    }
});

