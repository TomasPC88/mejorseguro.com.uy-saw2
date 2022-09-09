/*
In case it is needed JS Bootstrap components
*/
window.bsn = require('bootstrap.native/dist/bootstrap-native-v4.min');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Gallery = require('flickity-imagesloaded');
require('flickity-fullscreen');
require('flickity-as-nav-for');
require('flickity-bg-lazyload');

require('./functions');
require('./nav');
require('./sad-page-globals');

