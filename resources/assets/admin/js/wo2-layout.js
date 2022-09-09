import * as Ps from 'perfect-scrollbar';

import Wo2Variables from './wo2-variables';
import Wo2Search from './wo2-search';

export default class Wo2Layout {
    constructor() {
        this.vars           = Wo2Variables
        this.nav            = { menu_btn: null }
        this.menu           = { container: null }
        this.overlay        = null
        this.menu_active    = false
        this.search         = null
    }

    init() {

        this.search = new Wo2Search(
            document.querySelector('#wo2-search'),
            document.querySelector('.wo2_searchList')
        );
        this.nav.menu_btn   = document.querySelector(this.vars.nav_bar.menu_btn);
        this.menu.container = document.querySelector(this.vars.menu.container);
        this.overlay = document.querySelector(this.vars.overlay.container);

        Ps.initialize(this.menu.container, {
            wheelSpeed: 1,
            minScrollbarLength: 20
        });

        this.nav.menu_btn.addEventListener('click', this.menuToggle.bind(this));
        this.overlay.addEventListener('click', this.menuToggle.bind(this));

        document.querySelector('.pageloader').classList.remove('is-active');
    }

    menuToggle(ev) {
        if(!this.menu_active) {
            this.menu.container.classList.add('is-active');
            this.overlay.classList.add('is-active');
            this.menu_active = true;
        } else {
            this.menu.container.classList.remove('is-active');
            this.overlay.classList.remove('is-active');
            this.menu_active = false;
        }
    }
}
