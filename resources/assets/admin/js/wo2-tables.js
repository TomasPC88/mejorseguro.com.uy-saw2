import swal from 'sweetalert2';
import * as Ps from 'perfect-scrollbar';
import Mark from 'mark.js';
import {Plugins, Sortable} from '@shopify/draggable';

export default class Wo2Tables {

    constructor({
                    table = '.wo2-table',
                    row_options_menu = '.wo2-table-options',
                    table_search = '.wo2-table-search',
                    section_path = null,
                    sortable = false
                } = {}) {
        this.actives = [];
        this.prefix = 'wo2-table';
        this.table = $(table);
        this.row_options_menu = $(row_options_menu);
        this.row_options = [];
        this.section_path = section_path;
        this.section_prefix = this.section_path.split('/admin/')[1].replace('/', '');
        this.table_search = $(table_search);
        this.has_pagination = $('nav.pagination').length > 0;
        this.page_rows = [];
        this.sort = null;
        this.relation = null;
        this.order_column = null;
        this.order_type = null;
        this.order_saved = true;
        this.has_dragged_unsaved = false;
        this.settings = {
            row: {
                active: 'wo2-table-row_active'
            }
        };
        this.thumb_hover_intent = null;
        this.sortable = sortable;
        this.resort_button_template = `<button class="button is-outlined wo2-table-options__guardar-orden is-hidden" data-ttip="Guardar orden" data-ttip-pos="down">
            <span class="icon"><i class="mdi mdi-sort"></i></span>
        </button>`;
        this.reset_button_template = `<button class="button is-outlined is-warning wo2-table-options__reiniciar-orden is-hidden" style="margin-left:5px;" data-ttip="Reiniciar orden" data-ttip-pos="down">
            <span class="icon"><i class="mdi mdi-undo"></i></span>
        </button>`;

        this.draggableArea = `<div class="bucket">
                <div class="bucket-container">
                    <h4><i class="fa fa-info-circle"></i> Arrastre aquí para mover a otra página</h4>
                    <div class="bucket-table-box drag-container">
                    </div>
                    <span id="pull"><i class="fa fa-angle-left"></i></span>
                </div>
            </div>`;
        this.itemList = JSON.parse(localStorage.getItem('itemList'));
        this.itemList = this.itemList || [];
        this.init();
    }

    init() {
        let _self = this;

        if (this.sortable)
            this.initDraggable();

        // Row click event
        this.table.find('tbody > tr').forEach((tr) => {
            $(tr).on('click', this.rowClick.bind(this));
        });

        // Options array
        this.row_options_menu.find('.row-option').forEach((element) => {
            _self.row_options.push({
                element: $(element),
                href: $(element).attr('href'),
                is_hidden: false
            });
        });

        // Hidden Options array
        this.row_options_menu.find('.row-option-hidden').forEach((element) => {
            _self.row_options.push({
                element: $(element),
                href: $(element).attr('href'),
                is_hidden: true
            });
        });

        if (!this.has_pagination) {
            let rows = _.map(this.table.find('table:not(.drag-can) > tbody > tr'), (el, index) => {
                let row_object = {id: $(el).attr('id'), fields: []};
                _.each($(el).find('td:not(:first-child)'), (td) => {
                    if ($(td).find('figure').length === 0) {
                        row_object.fields.push($(td).text());
                    }
                });

                return row_object;
            });

            this.page_rows = rows;
        }

        this.row_options_menu.append(this.resort_button_template);
        this.row_options_menu.find('.wo2-table-options__guardar-orden').on('click', this.guardarOrden.bind(this));

        this.row_options_menu.append(this.reset_button_template);
        this.row_options_menu.find('.wo2-table-options__reiniciar-orden').on('click', this.reiniciarOrden.bind(this));

        let url_regex = /order=(.+)\|(asc|desc)(&relation=([a-z]+))?(&sort=([a-z]+))?/.exec(decodeURI(window.location.href));

        this.order_column = (url_regex !== null) ? url_regex[1] : null;
        this.order_type = (url_regex !== null) ? url_regex[2] : 'asc';
        this.relation= (url_regex !== null) ? url_regex[4] : null;
        this.sort= (url_regex !== null) ? url_regex[6] : null;
        this.isSavedOrder();

        // this.has_resort = localStorage.getItem(this.section_prefix+'__has_resort') !== null;

        // Search event
        this.table_search.on('keyup', this.search.bind(this));

        // Delete button
        this.row_options_menu.find('.' + this.prefix + '-options__delete').on('click', this.delete.bind(this));

        // Upload Mercadolibre
        this.row_options_menu.find('.' + this.prefix + '-options__meli').on('click', this.meli.bind(this));

        // Update Mercadolibre
        this.row_options_menu.find('.' + this.prefix + '-options__meli_update').on('click', this.meli_update.bind(this));

        // Delete Mercadolibre
        this.row_options_menu.find('.' + this.prefix + '-options__meli_delete').on('click', this.meli_delete.bind(this));

        this.niceTable();
    }

    initDraggable() {
        if (this.has_pagination)
            $('body').append(this.draggableArea);

        //Activo el draggable para todos los contenedores
        const containers = document.querySelectorAll('.drag-container');
        if (containers.length === 0) {
            return false;
        }
        const sortable = new Sortable(containers, {
            delay: 100,
            handle: '.handle',
            draggable: '.can-drag',
            classes: {
                mirror: 'tile'
            },
            mirror: {
                constrainDimensions: true,
            },
            plugins: [Plugins.ResizeMirror],
        });

        let index = this.itemList.findIndex(item => item.section === this.section_prefix);
        if (index === -1) {
            this.itemList.push(
                {
                    section: this.section_prefix,
                    bucket: [],
                    ids: [],
                }
            );
            index = this.itemList.length - 1;
        }

        const build_template = (html) => {
            let id = html.match(/id="(\d+)"/)[1];
            let img = html.match(/src="(.+)"/);
            let name = html.match(/class="name">(.+)</);
            return `<div id="${id}" class="can-drag handle tile">
                       <span>${id} - ${name ? name[1] : ''}</span>
                         ${img ? '<img src="' + img[1] + '" alt="">' : ''}
                  </div>`
        };

        // Reordenar DOM contra lista de ids
        this.itemList[index].ids.sort((a, b) => {
            return a.prev - b.prev;
        }).forEach(({prev, pos, html}) => {
            this.table.find(`tr[id="${pos}"]`).remove();
            if (!prev)
                this.table.find(`tr.can-drag:first-child`).before(html);
            else {
                const prevRow = this.table.find(`tr[id="${prev}"]`);
                if (prevRow)
                    prevRow.after(html);
            }
        });

        let draggableArea = null;

        if (this.has_pagination) {
            let html = '';
            // Busco en Local Storage los elementos para montar sobre la tabla y los cargo en el dropZone
            draggableArea = document.querySelector('.bucket');
            let span = draggableArea.querySelector('span#pull');

            const open = function (is = true) {
                if (is) {
                    draggableArea.classList.remove('left');
                    this.querySelector('i').classList.replace('fa-angle-right', 'fa-angle-left');
                    return
                }

                draggableArea.classList.add('left');
                this.querySelector('i').classList.replace('fa-angle-right', 'fa-angle-left');
            };

            //Listeners para el span dentro del dropZone
            ['mouseover', 'click'].forEach((evt) => {

                draggableArea.addEventListener(evt, function (ev) {
                    if (evt === 'mouseover') {
                        // ev.stopPropagation();
                        if (sortable.isDragging())
                            open.bind(this,false)()
                    }
                });

                span.addEventListener(evt, function () {
                    if (evt !== 'mouseover') {
                        open.bind(this,draggableArea.classList.contains('left'))();
                        return
                    }

                    if (sortable.isDragging()){
                        open.bind(this,false)()
                    }

                });
            });


            //Elimino los duplicados desde lo q tengo en la zona draggable
            if (this.itemList[index].bucket.length) {
                this.itemList[index].bucket.forEach((item) => {
                    const el = this.table.find(`tr[id="${item.pos}"]`);
                    if (el) {
                        html += build_template(item.html) + '\n';
                        el.remove()
                    }
                });
                draggableArea.querySelector('.drag-container').innerHTML = html;
                span.dispatchEvent(new Event('click'));
            }
        }

        const page = (new URL(window.location.href)).searchParams.get('page') || 1;

        //Incluyo el grabbing cursor
        sortable.on('drag:start', (evt) => {
            this.table.find('tr.wo2-table-row_active').removeClass('wo2-table-row_active');
            const el = evt.source;
            el.style.cursor = 'grabbing';
            el.classList.add('wo2-table-row_active');
        });

        //Escucho el cambio de contenedor para actualizar el localStorage
        sortable.on('sortable:stop', (evt) => {
            //Elimino algun mirror que se quede colgado
            setTimeout(() => {
                let mirror = document.querySelector('tr.draggable-mirror');
                if (mirror !== null)
                    mirror.remove();
            }, 50);

            const el = evt.dragEvent.source;
            el.style.removeProperty('cursor');
            el.classList.remove('wo2-table-row_active');
            el.classList.remove('draggable-source--is-dragging');
            const pos = el.id;
            const prev = el.previousElementSibling ? el.previousElementSibling.id : 0;

            const putInList = (i, template) => {
                if (i === -1)
                    this.itemList[index].ids.push({
                        prev: prev,
                        pos: pos,
                        html: template || el.outerHTML,
                        page: page
                    });
                else
                    this.itemList[index].ids[i] = {
                        prev: prev,
                        pos: pos,
                        html: el.outerHTML,
                        page: page
                    };

                this.has_dragged_unsaved = true;
                this.toggleSortButton(true);
            };

            if (evt.newContainer.tagName === 'TBODY') {
                let i = -1;
                if (evt.oldContainer.tagName === 'DIV') {
                    i = this.itemList[index].bucket.findIndex(item => item.pos === pos);
                    if (i !== -1) {
                        const replacement = document.createElement('tr');
                        el.previousElementSibling ? el.previousElementSibling.after(replacement) : el.parentNode.prepend(replacement);
                        replacement.outerHTML = this.itemList[index].bucket[i].html;
                        setTimeout(() => {
                            evt.dragEvent.originalSource.remove();
                            el.remove();
                        });
                        putInList(-1, this.itemList[index].bucket[i].html);
                        this.itemList[index].bucket = this.itemList[index].bucket.filter(item => item.pos !== pos);
                        evt.newContainer.querySelector(`tr[id="${pos}"]`).addEventListener('click',this.rowClick.bind(this));
                    }
                }
                else {
                    i = this.itemList[index].ids.findIndex(id => id.pos === pos);

                    putInList(i);

                    //Reordeno el storage basado en el DOM
                    setTimeout(() => {
                        this.itemList[index].ids.map((id) => {
                            let el = this.table[0].querySelector(`tr[id="${id.pos}"]`);
                            id.prev = el ? el.previousElementSibling ? el.previousElementSibling.id : 0 : 0;
                        });
                    }, 100);

                }

            } else {
                this.hideOptions();
                const i = this.itemList[index].bucket.findIndex(obj => obj.pos === pos);
                this.itemList[index].ids = this.itemList[index].ids.filter(id => id.pos !== pos);
                if (i === -1) {
                    this.itemList[index].bucket.push({
                        pos: pos,
                        html: el.outerHTML
                    });
                    const replacement = document.createElement('DIV');
                    el.parentNode.appendChild(replacement);
                    replacement.outerHTML = build_template(el.outerHTML);
                    setTimeout(() => {
                        evt.dragEvent.originalSource.remove();
                    });
                }
            }

            localStorage.setItem('itemList', JSON.stringify(this.itemList));
        });
    }

    isSavedOrder() {
        //Pregunto si hay guardado orden por columna y tipo
        if (this.order_column && this.order_type) {
            let current_btoa = btoa(
                this.section_prefix +
                this.order_column +
                this.order_type
            );

            if (current_btoa != localStorage.getItem('last_saved')) {
                this.order_saved = false;
                this.toggleSortButton(true);
            }
        }
        //Pregunto si hay guardado por accion de arrastre
        const index = this.itemList.findIndex(item => item.section === this.section_prefix);
        if (index !== -1 && this.itemList[index].ids.length > 0) {
            this.has_dragged_unsaved = true;
            this.toggleSortButton(true);
        }
    }

    niceTable() {
        _.each(this.table.find('thead > tr > th'), (el, index) => {
            let padding = 10;
            let text = $(el).text();

            if (el.dataset.col !== undefined) {

                let is_current = (el.dataset.col == this.order_column) ? 'current' : '';
                let order_type = (is_current == 'current' && this.order_type == 'asc') ? 'desc' : 'asc';

                let url = `${ this.section_path }?order=${ el.dataset.col }|${order_type}`;
                url = el.dataset.rel?`${url}&relation=${el.dataset.rel}`:url;
                url = el.dataset.sort?`${url}&sort=${el.dataset.sort}`:url;

                $(el).empty().html(`<div class="th-inner${(index === 0 ? ' first' : '')}">
                    <a class="${is_current}" href="${url}">${text}
                        <span class="icon">
                            <i class="mdi mdi-chevron-${(order_type == 'asc' ? 'down' : 'up')}"></i>
                        </span>
                    </a>
                </div>`);
            } else {
                $(el).empty().html(`<div class="th-inner${(index === 0 ? ' first' : '')}">
                    ${text}
                </div>`);
            }

            let textWidth = $(el).children('div')[0].offsetWidth;
            $(el).css({'min-width': 'calc(0.75em + ' + (textWidth + padding) + 'px)'});
        });

        _.each(this.table.find('tbody td:first-child'), (el) => {
            $(el).prepend(`<div class="drag-handler">
                <span class="icon is-medium handle">
                    <i class="mdi mdi-drag-vertical mdi-24px"></i>
                </span>
            </div>`);
        });

        _.each(this.table.find('tbody > tr > td > figure > img[src$=".jpg"]'), (el, index) => {
            let img_src = $(el).attr('src');
            let img_thumb = $(el)[0].dataset.thumb;
            let parent = $(el).parent();
            let container = parent.parent();
            container.empty().html('<div class="dropdown"></div>').children().append(parent).append(`<div class="dropdown-menu" role="menu">
                <div class="dropdown-content">
                    <div class="dropdown-item">
                        <div class="notification has-text-centered">
                            Cargando <i class="fa fa-circle-o-notch fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>`);

            parent.data('thumb', img_thumb);
            parent.css({'cursor': 'zoom-in'});

            parent.on('mouseover', this.loadThumbnail.bind(this));
            parent.on('mouseout', this.unloadThumbnail.bind(this));
        });

        // Table scrollbar
        Ps.initialize(this.table.find('.table-holder')[0], {
            wheelSpeed: 0.5,
            minScrollbarLength: 20,
            suppressScrollX: true
        });

    }

    loadThumbnail(ev) {
        let figure = $(ev.target).closest('figure');
        let img_src = figure[0].dataset.thumb;
        let holder = figure.parent();
        let dd = holder.find('.dropdown-item');
        holder.addClass('is-active');

        if (dd.find('img').length > 0) return;

        this.thumb_hover_intent = setTimeout(() => {
            axios.get(img_src).then((res) => {
                if (res.status == 200) dd.empty().append('<img style="background-color: #b5b5b5;" src="' + res.request.responseURL + '" />')
            }).catch(err => console.log(err));
        }, 500);
    }

    unloadThumbnail(ev) {
        let figure = $(ev.target).closest('figure');
        let holder = figure.parent();
        holder.removeClass('is-active');

        if (this.thumb_hover_intent !== null) clearTimeout(this.thumb_hover_intent);
    }

    rowClick(ev) {
        let id = $(ev.target).closest('tr').attr('id');

        if (_.indexOf(this.actives, id) < 0) {
            this.actives = [];

            let meli = false;

            this.actives.push(id);
            $(ev.target).closest('tr').addClass('wo2-table-row_active').siblings().removeClass('wo2-table-row_active');
            this.hideOptions($(ev.target).closest('tr').attr('id'));
            if ($(ev.target).closest('tr').hasClass('meli_id')) {
                meli = true;
            }
            this.showOptions($(ev.target).closest('tr').attr('id'), meli);
        } else {
            _.pull(this.actives, id);
            $(ev.target).closest('tr').removeClass('wo2-table-row_active');
            this.hideOptions($(ev.target).closest('tr').attr('id'));
        }
    }

    toggleSortButton(opt) {
        if (opt) {
            this.row_options_menu.find('.wo2-table-options__guardar-orden').removeClass('is-hidden');
        } else {
            this.row_options_menu.find('.wo2-table-options__guardar-orden').addClass('is-hidden');
        }

        if (this.has_dragged_unsaved) {
            this.row_options_menu.find('.wo2-table-options__reiniciar-orden').removeClass('is-hidden');
        } else {
            this.row_options_menu.find('.wo2-table-options__reiniciar-orden').addClass('is-hidden');
        }
    }

    showOptions(id, meli) {
        this.row_options.forEach((button) => {
            // console.log(button.element.attr('href').split("/"));
            button.element.attr('href', button.href.replace('%id%', id));
            if (button.is_hidden && meli) {
                if (button.element.attr('href').split("/")[5] != "meli") {
                    button.element.removeClass('is-hidden');
                }
            } else {
                if (button.element.attr('href').split("/")[5] != "meli_delete" && button.element.attr('href').split("/")[5] != "meli_update") {
                    button.element.removeClass('is-hidden');
                }
            }
        });
    }

    hideOptions() {
        this.row_options.forEach((button) => {
            button.element.attr('href', button.href);
            if (button.is_hidden) {
                button.element.addClass('is-hidden');
            }
        });
    }

    search(ev) {
        if (!this.has_pagination) {
            let keycode = ev.keyCode;

            let valid =
                (keycode > 47 && keycode < 58) || // number keys
                keycode == 32 || keycode == 13 || keycode == 8 || // spacebar & return key(s) (if you want to allow carriage returns)
                (keycode > 64 && keycode < 91) || // letter keys
                (keycode > 95 && keycode < 112) || // numpad keys
                (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
                (keycode > 218 && keycode < 223);   // [\]' (in order)

            if (!valid) return;

            let search_query = ev.target.value.toLowerCase();
            let results = [];

            let highlight = new Mark(this.table.find('table:not(.drag-can) > tbody')[0]);
            highlight.unmark();

            if (/^id\:(\d+)$/.test(search_query) === true) {
                let id = /^id\:(\d+)$/.exec(search_query)[1];
                results = _.reject(this.page_rows, {'id': id});
                highlight.mark(id, {
                    exclude: ['td:not(:first-child) *']
                });
            } else {
                results = _.reject(this.page_rows, (o) => {
                    for (let i in o.fields) {
                        if (o.fields[i].toLowerCase().includes(search_query)) return true;
                    }
                    return false;
                });
                highlight.mark(search_query);
            }

            this.table.find('table:not(.drag-can) > tbody > tr.is-hidden').removeClass('is-hidden');

            if (results.length > 0) {
                _.forEach(results, (o) => {
                    this.table.find('table:not(.drag-can) > tbody > tr[id="' + o.id + '"]').addClass('is-hidden');
                });
            }

            $(ev.target).toggleClass('is-danger', (results.length === this.page_rows.length));
            $(ev.target).closest('p').find('span:last-child > i').toggleClass('fa-ban', (results.length === this.page_rows.length));

            return;

        }
        if (ev.keyCode != 13) return;

        if (ev.target.value !== '') {
            window.location = this.section_path + 'buscar/' + ev.target.value;
        } else {
            window.location = this.section_path;
        }
    }

    delete(ev) {
        let _self = this;

        ev.preventDefault();
        $(ev.target).closest('a')[0].blur();
        let url = $(ev.target).closest('a').attr('href');
        let error = false;

        swal.queue([{
            title: '¿Está seguro?',
            text: 'Una vez borrada la entrada no se podra recuperar',
            //type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                return axios.delete(url).then((response) => {
                    switch (response.data.status) {
                        case 'OK':
                            swal.insertQueueStep    ({
                                title: "¡Eliminado!",
                                //type: 'success',
                                allowOutsideClick: false,
                            });
                            window.location = (_self.section_path !== undefined || _self.section_path !== '') ? _self.section_path : response.data.section_path;
                            break;
                        case 'WARNING':
                            swal.insertQueueStep({
                                title: 'Esta seguro?',
                                text: response.data.message,
                                //type: 'warning',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                showLoaderOnConfirm: true,
                                confirmButtonText: 'Si',
                                cancelButtonText: 'Cancelar',
                                preConfirm:()=>{
                                    return axios.get(url, {
                                        params: {
                                            confirm: true
                                        }
                                    })
                                        .then((response) => {
                                            if (response.data.status == 'OK') {
                                                window.location = (_self.section_path !== undefined || _self.section_path !== '') ? _self.section_path : response.data.section_path;
                                            }
                                        })
                                        .catch((e) => {
                                            console.log(e);
                                        })
                                }
                            });
                            break;
                        default:
                            error = true;
                            swal.insertQueueStep({
                                title: "Lo sentimos",
                                text: response.data.errors[0],
                                //type: 'error',
                                allowOutsideClick: false,
                            });
                    }
                });
            }
        }]).then(() => {
            if (!error) {
                //window.location = (_self.section_path !== undefined || _self.section_path !== '') ? _self.section_path : response.data.section_path;
            }
        }, (dis) => {
            //cancel
        });
    }

    toggle(ev) {
        let _self = this;

        ev.preventDefault();
        $(ev.target).closest('a')[0].blur();
        let url = $(ev.target).closest('a').attr('href');

        swal({
            title: '¿Activar/Desactivar?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
        }).then(() => {
            axios.get(url).then((response) => {
                if (response.data.status == 'OK') {
                    window.location = (_self.section_path !== undefined || _self.section_path !== '') ? _self.section_path : response.data.section_path;
                }
            });
        }, (dis) => {
            //cancel
        });
    }

    meli(ev) {
        let _self = this;

        ev.preventDefault();
        $(ev.target).closest('a')[0].blur();
        let url = $(ev.target).closest('a').attr('href');

        let error = false;
        let id;

        swal.queue([{
            title: '¿Publicar vehículo?',
            text: 'Este vehículo será publicado en Mercadolibre',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            allowEscapeKey: false,
            preConfirm: () => {
                return axios.get(url).then((data) => {
                    if (data.data.error != "") {
                        error = true;
                        if (data.data.error == "¡Publicación actualizada!") {
                            swal.insertQueueStep({
                                title: data.data.error,
                                text: data.data.cause.message,
                                html: '<br/><strong>¡Importante!</strong><br/>' +
                                'Los campos actualizados son: <strong>Título</strong>, <strong>Descripción</strong>, <strong>Precio</strong> e <strong>Imágenes</strong>',
                                type: 'success',
                                allowOutsideClick: false,
                            });
                        } else {
                            swal.insertQueueStep({
                                title: data.data.error,
                                html: '<strong>Code</strong>: ' + data.data.cause.code + '<br /><br />' +
                                '<strong>Error</strong>: ' + data.data.cause.message,
                                type: 'error',
                            });
                        }
                    } else {
                        id = data.data.id;
                        swal.insertQueueStep({
                            title: data.data.success,
                            text: data.data.message,
                            type: 'success',
                            allowOutsideClick: false,
                        });
                    }
                });
            },
        }]).then(() => {
            if (!error) {
                let table = this.table.find('tbody > tr');
                let tr = $(table).find("#" + id);
                $(tr).removeClass("wo2-table-row_active");
                $(tr).addClass("meli_id");
                let wo_tr = $(tr).find('td').eq(7);

                $(wo_tr).find("span").removeClass("has-text-grey-lighter");
                $(wo_tr).find("span > i").removeClass("fa-times-circle");

                $(wo_tr).find("span").addClass("has-text-success");
                $(wo_tr).find("span > i").addClass("fa-check-circle");

                _.pull(this.actives, id);

                this.hideOptions($(tr).attr("id"));
            }
        }, (dis) => {
            //cancel
        });
    }

    meli_update(ev) {
        let _self = this;

        ev.preventDefault();
        $(ev.target).closest('a')[0].blur();
        let url = $(ev.target).closest('a').attr('href');

        swal.queue([{
            title: '¿Actualizar publicación?',
            text: 'Este vehículo será actualizado en Mercadolibre',
            html: '<strong>¡Importante!</strong><br/>' +
            'Los campos que serán actualizados son: <strong>Título</strong>, <strong>Descripción</strong>, <strong>Precio</strong> e <strong>Imágenes</strong>',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            allowEscapeKey: false,
            preConfirm: () => {
                return axios.get(url).then((data) => {
                    if (data.data.error != "") {
                        if (data.data.error == "¡Publicación actualizada!") {
                            swal.insertQueueStep({
                                title: data.data.error,
                                text: data.data.cause.message,
                                type: 'success',
                                allowOutsideClick: false,
                            });
                        }
                    }
                });
            },
        }]).then(() => {
            // window.location = (_self.section_path !== undefined || _self.section_path !== '') ? _self.section_path : response.data.section_path;
        }, (dis) => {
            //cancel
        });
    }

    meli_delete(ev) {
        let _self = this;

        ev.preventDefault();
        $(ev.target).closest('a')[0].blur();
        let url = $(ev.target).closest('a').attr('href');

        let error = false;
        let id;

        swal.queue([{
            title: '¿Eliminar publicación?',
            text: 'Este vehículo será removido de Mercadolibre',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                return axios.get(url).then((data) => {
                    if (data.data[0].error != "") {
                        error = true;
                        swal.insertQueueStep({
                            title: data.data[0].error,
                            text: data.data[0].cause.message,
                            type: 'error',
                            allowOutsideClick: false,
                        });
                    } else {
                        id = data.data[0].id;
                        swal.insertQueueStep({
                            title: data.data[0].success,
                            text: data.data[0].message,
                            type: 'success',
                            allowOutsideClick: false,
                        });
                    }
                });
            },
        }]).then((data) => {
            if (!error) {
                let table = this.table.find('tbody > tr');
                let tr = $(table).find("#" + id);
                $(tr).removeClass("wo2-table-row_active");
                $(tr).removeClass("meli_id");
                let wo_tr = $(tr).find('td').eq(7);
                $(wo_tr).find("span").removeClass("has-text-success");
                $(wo_tr).find("span > i").removeClass("fa-check-circle");

                $(wo_tr).find("span").addClass("has-text-grey-lighter");
                $(wo_tr).find("span > i").addClass("fa-times-circle");

                _.pull(this.actives, id);

                this.hideOptions($(tr).attr("id"));
            }
        }, (dis) => {
            //cancel
        });
    }

    reiniciarOrden() {
        this.itemList = this.itemList.filter(item => item.section !== this.section_prefix);
        localStorage.setItem('itemList', JSON.stringify(this.itemList));
        location.reload();
    }

    guardarOrden(ev) {
        let _self = this;
        ev.preventDefault();
        $(ev.target).closest('button')[0].blur();


        // console.log( this.section_path );
        let post_url = this.section_path + 'order';
        let data = {};
        //
        // console.log( this.sortable.toArray() );
        // let sorted_items = this.sortable.toArray().map( (el, ind) => {
        //     console.log(el, ind);
        // });

        const index = this.itemList.findIndex(item => item.section === this.section_prefix);
        if (index !== -1 && this.itemList[index].ids.length > 0) {
            data.dragged = this.itemList[index].ids;
            localStorage.removeItem('last_saved');
        }

        if (!this.order_saved) {
            localStorage.removeItem('last_saved');
            data.order = {
                column: this.order_column,
                type: this.order_type,
                relation: this.relation,
                sort: this.sort
            }
        }

        swal.queue([{
            title: 'Esta seguro?',
            text: 'El orden que se guarde usando esta accion se vera reflejado en el sitio',
            type: 'warning',
            showLoaderOnConfirm: true,
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                return axios.post(post_url, data).then((response) => {
                    if (!this.order_saved) {
                        localStorage.setItem('last_saved', btoa(
                            this.section_prefix +
                            this.sort+
                            this.relation +
                            this.order_column +
                            this.order_type
                        ));
                    }

                    this.itemList = this.itemList.filter(item => item.section !== this.section_prefix);
                    localStorage.setItem('itemList', JSON.stringify(this.itemList));

                    this.has_dragged_unsaved = false;
                    this.toggleSortButton(false);

                    if (response.data.status == 'OK') {
                        window.location.href = window.location.origin + window.location.pathname;
                        return
                    }

                    swal({
                        title: 'Error',
                        text: response.data.errors[0],
                        type: 'error',
                    }).then(() => {
                        window.location.reload();
                    })
                });
            }
        }]);

    }
}
