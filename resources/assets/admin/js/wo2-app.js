require('./bootstrap');

import Wo2Layout from './wo2-layout';
import Wo2Forms from './wo2-forms';
import Wo2Tables from './wo2-tables';

window.datepicker = require("flatpickr");
window.datepicker_es = require("flatpickr/dist/l10n/es.js").default.es;
window.moment = require('moment');

const wo2_layout = new Wo2Layout();

window.Wo2Forms = Wo2Forms;
window.Wo2Tables = Wo2Tables;

$(document).ready(() => {
    wo2_layout.init();
});