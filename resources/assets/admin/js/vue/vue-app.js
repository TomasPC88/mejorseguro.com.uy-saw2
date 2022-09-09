//Vue app
import Toasted from "vue-toasted";
import AsyncComputed from 'vue-async-computed'
import Vue from 'vue'
import Vuex from 'vuex'
import Store from './store/index'

window.Vue = require('vue');

Vue.use(Vuex);
Vue.use(Toasted);
Vue.use(AsyncComputed);

const store = new Vuex.Store(Store)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('selectMultiple', require('./components/select/SelectMultipleWithForms'));
Vue.component('selectSimple', require('./components/select/SelectSimple'));
Vue.component('selectTwo', require('./components/select/SelectTwo'));
Vue.component('modal', require('./components/modal/Modal'));
Vue.component('custom', require('./components/Custom'));

function addRef(el, binding, vnode) {
    const ref = binding.arg;
    const vm = vnode.context;
    if (!vm.$refs.hasOwnProperty(ref)) {
        vm.$refs[ref] = []
    }
    const index = vm.$refs[ref].indexOf(el);
    if (index == -1) {
        vnode.context.$refs[ref].push(el)
    }
}

function removeRef(el, {arg: ref}, {context: vm}) {
    if (vm.$refs.hasOwnProperty(ref)) {
        const arr = vm.$refs[ref];
        const index = arr.indexOf(el);
        if (index) {
            arr.splice(index, 1)
        }
    }
}

Vue.directive('multi-ref', {
    bind: addRef,
    update: addRef,
    unbind: removeRef
});

export const Channel = new Vue();
let data = {
    baseUrl: '/admin'
};
Vue.mixin({
    data() {
        return data;
    },
    methods: {
        toast(type, text) {
            eval(`this.$toasted.${type}('${text}', {
                theme: 'outline',
                duration:1500
        })`);
        },
        saveValidation(fields) {
            fields.map((item) => {
                wo2_form.saveElement($(item))
            });
        },
        removeValidation(fields) {
            fields.map((item) => {
                wo2_form.pullElement($(item))
            });
        },
        dateFormat(date, options = null) {
            if (!options)
                options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};

            return new Date(date).toLocaleString('es-ES', options);
        }
    },
    filters: {
        pluralize: function (vowl = false, value) {
            return vowl ? `${value}s` : `${value}es`;
        },
        dateFormat(date, options = null) {
            if (!options)
                options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};

            return new Date(date).toLocaleString('es-ES', options);
        },
        statusName(id, statuses) {
            let status = statuses.find((comparable) => {
                return comparable.id === id;
            });

            return status ? status.name : '';
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
    store
});