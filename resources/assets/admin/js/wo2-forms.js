import Wo2Validate from './wo2-validate.js';
import Wo2Utils from './wo2-utils.js';
import Wo2Fileupload from './wo2-fileupload.js';
import serialize from 'form-serialize';
import swal from 'sweetalert2';
import Quill from 'quill';

export default class Wo2Forms {

    constructor({
                    form = '.wo2-form',
                    tabs = '.wo2-form-tabs',
                    submitButton = '.wo2-form-button',
                    submitButtonExtra = '.wo2-form-button-extra',
                    deleteButton = '.wo2-form-delete-button',
                    returnPath = null,
                    errorList = '.error-list',
                    uploader = '.wo2-fileupload',
                    uploaderData = []
                } = {}) {
        this.form = $(form);
        this.tabs = $(tabs);
        this.errorList = $(errorList);
        this.submit_button = $(submitButton);
        this.submit_button_extra = submitButtonExtra;
        this.delete_button = $(deleteButton);
        this.return_path = returnPath;
        this.next_action = null;

        this.action = this.form.attr('action');
        this.elements = [];
        this.loading = false;
        this.uploaders = [];

        this.settings = {
            icon: {error: 'fa-warning', success: 'fa-check'},
            quill: {
                toolbar: `<div class="quill-toolbar">
                    <span class="ql-formats">
                        <select class="ql-size">
                            <option value="small">Pequeño</option>
                            <option selected="selected">Normal</option>
                            <option value="large">Grande</option>
                            <option value="huge">Enorme</option>
                        </select>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-bold"></button>
                        <button class="ql-italic"></button>
                        <button class="ql-underline"></button>
                        <button class="ql-strike"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-list" value="ordered"></button>
                        <button class="ql-list" value="bullet"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-link"></button>
                        <button class="ql-clean"></button>
                    </span>
                </div>`
            }
        };

        this.onValidationComplete = this.validation_complete;
        this.onSend = this.send;

        // let _self = this;
        // if (uploader !== false) {
        //     $(uploader).forEach((uloader) => {
        //         let initialData = _.filter(uploaderData, ['group', (uloader.dataset.group === undefined ? 'normal' : uloader.dataset.group)]);
        //         _self.uploaders.push(new Wo2Fileupload(uloader, returnPath, initialData, uloader.dataset));
        //     });
        // }

        this.initUploaders(uploader, uploaderData, returnPath);

        this.init();
    }

    initUploaders(uploader = '.wo2-fileupload', uploaderData = [], returnPath = null, uuid = null) {
        let _self = this;
        if (uploader !== false) {
            $(uploader).forEach((uloader) => {
                let initialData = _.filter(uploaderData, ['group', (uloader.dataset.group === undefined ? 'normal' : uloader.dataset.group)]);
                _self.uploaders.push(new Wo2Fileupload(uloader, returnPath, initialData, uuid, uloader.dataset));
            });
        }
        return this.uploaders;
    }

    getUploader(uuid) {
        return this.uploaders.filter((item) => {
            return item.uuid === uuid;
        })
    }

    removeUploader(uuid) {
        this.uploaders = this.uploaders.filter((item) => {
            return item.uuid !== uuid;
        })
    }

    hasUploader(uuid) {
        return this.getUploader(uuid).length > 0
    }

    saveElement(el, params = {quill: null, uuid: null}) {
        let quill = params.quill ? params.quill : null;
        let uuid = params.uuid ? params.uuid : Wo2Utils.uuid();
        el.data('woid', uuid);
        el.find('input,select,textarea').data('woid', uuid);
        let temp_element = {
            uuid: uuid,
            quill: quill,
            field: el,
            input: (quill !== null ? el.find('input[type="hidden"]') : el.find('input, textarea, select')),
            message: el.find('p.help'),
            icon: el.find('span.icon, div.icon'),
            label: el.find('label'),
            alias: el.data('alias'),
            value: null,
        };

        this.elements.push(temp_element);
        this.buildValidation(el);
    }

    buildValidation(el) {
        // console.log(el);
        let validation = (el.data('validation') !== undefined || el.data('validation') !== '' ? JSON.parse(el.data('validation').replace(/'/g, '"')) : []);
        let is_required = (validation.length > 0 && validation.indexOf('OPT') < 0) ? true : false;
        let is_optional = validation.indexOf('OPT') > -1;
        _.pull(validation, 'OPT');
        if (is_required) el.addClass('required');
        var element = this.elements.filter((item) => {
            return item.uuid == el.data('woid');
        })[0];
        element.required = is_required;
        element.optional = is_optional;
        element.validation = validation;
    }

    pullElement(el) {
        let selected;
        this.elements = this.elements.filter((item) => {
            if (item.uuid == el.data('woid'))
                selected = item;
            return item.uuid != el.data('woid');
        });
        return selected;
    }

    putElements(elements) {
        _.merge(this.elements, elements);
    }

    init() {
        let _self = this;
        this.tabs.find('li').on('click', function () {
            let li = $(this);
            li.siblings().removeClass('is-active');
            li.addClass('is-active');
            li.parent().parent().siblings().removeClass('is-active');
            _self.tabs.find(`.tab[data-lang=${li.data('tab')}]`).addClass('is-active');
        });

        this.form.find('.field:not(.ignore)').forEach(function (element) {
            let uuid = Wo2Utils.uuid();

            let quill = null;

            if ($(element).hasClass('is-quill')) {
                if ($(element).find('.quill-toolbar').length < 1) $(element).prepend(_self.settings.quill.toolbar);

                quill = new Quill($(element).find('.quill-editor')[0], {
                    theme: 'snow',
                    modules: {
                        toolbar: $(element).find('.quill-toolbar')[0]
                    }
                });
                quill.setContents(JSON.parse($(element).find('input[type="hidden"]').val()));
                $(element).data('woid', uuid);
                $(element).prepend('<label class="label">' + $(element).data('alias') + '</label>');
                $(element).append('<p class="help"></p>');
            } else {

                if ($(element).find('input, textarea, select')[0].type == 'checkbox') {
                    $(element).prepend('<label class="label">' + $(element).data('alias') + '</label>');
                    // en caso de querer agregarle un label a la derecha del checkbox, modificar la siguiente linea
                    $(element).append('<label for="' + uuid + '" class="label"></label>');
                    $(element).find('input').attr('id', uuid);
                } else {
                    $(element).prepend('<label class="label">' + $(element).data('alias') + '</label>');
                }
                $(element).append('<p class="help"></p>');

                if ($(element).find('input, textarea, select')[0].type == 'select-one' || $(element).find('input, textarea, select')[0].type == 'select-multiple') {
                    $(element).find('.control').append(`<div class="icon is-small is-left">
                        <i class="fa ` + ($(element).data('icon') !== undefined ? $(element).data('icon') : '') + `"></i>
                        </div>`);
                } else {
                    $(element).find('.control').append(`<span class="icon is-small is-right">
                        <i class="fa ` + ($(element).data('icon') !== undefined ? $(element).data('icon') : '') + `"></i>
                        </span>`);
                }
                $(element).data('woid', uuid);
                $(element).find('input, textarea, select').data('woid', uuid).on('input', _self.inputChange.bind(_self));

            }

            _self.saveElement($(element), {uuid: uuid, quill: quill});

        });


        this.submit_button.on('click', this.process.bind(this));
        $(this.submit_button_extra).on('click', this.process.bind(this));

        // this.submit_button.on('click', function(test) {
        //     _self.uploaders[0].upload(2, this.end);
        // });


        if (this.delete_button !== undefined) {
            this.delete_button.on('click', this.delete.bind(this));
        }
    }

    process(ev) {
        ev.stopPropagation();
        if (this.loading) return;

        if ($(ev.target).closest('button')[0].dataset.next !== undefined) {
            this.next_action = $(ev.target).closest('button')[0].dataset.next;
        } else {
            this.next_action = null;
        }

        this.loading = true;
        this.submit_button.addClass('is-loading');
        $('.wo-form-button-back').addClass('is-loading');
        $(this.submit_button_extra).forEach((el) => {
            $(el).addClass('is-loading');
        });

        this.errorList.addClass('is-hidden');
        let errors = this.validate();

        if (errors.length === 0) {
            console.log("Todo validado!");
            this.onValidationComplete(this.format_data(), this.next_action);
        } else {
            errors.forEach(({error})=>{
                this.errorList.find('ul').append(`<li>${error}</li>`)
            });
            this.errorList.removeClass('is-hidden');
            this.tabs.forEach((tab)=>{
                $(tab).find('input').forEach((input)=>{
                    let index = errors.findIndex(({uuid})=> uuid === input.dataset.woid);
                    if(index!==-1){
                        let target = $(input).closest('.tab').data('lang');
                        $(tab).find(`.tabs li[data-tab=${target}]`).trigger('click');
                    }
                })
            });
            // console.log(errors);
            // console.log("Aun quedan errores :(");
            this.loading = false;
            this.submit_button.removeClass('is-loading');
            $('.wo-form-button-back').removeClass('is-loading');
            $(this.submit_button_extra).forEach((el) => {
                $(el).removeClass('is-loading');
            });
        }
    }

    validate(){
        this.errorList.find('ul > li').remove();
        let errors = [];
        this.elements.forEach((element) => {
            let result = true;
            let validate = false;

            if (element.quill !== null) {
                element.value = element.quill.getText().trim();
                if (element.value == "") {
                    element.input.val("");
                } else {
                    element.input.val(JSON.stringify(element.quill.getContents()));
                }
            } else {
                element.value = element.input.val();
            }

            if (element.required) {
                validate = true;
            } else if (element.optional && element.value !== undefined && element.value !== '') {
                validate = true;
            }


            if (validate) {
                result = Wo2Validate.validate(element.value, element.validation);
            }

            if (result === true) {
                if (element.quill !== null) {
                    element.field.removeClass('is-danger').addClass('is-success');
                } else {
                    if (element.input[0].type == "select-one" || element.input[0].type == "select-multiple") {
                        element.input.parent().removeClass('is-danger').addClass('is-success');
                    } else {
                        element.input.removeClass('is-danger').addClass('is-success');
                    }
                }

                element.icon.find('i').removeClass(this.settings.icon.error).addClass(this.settings.icon.success);
                element.message.text('');
            } else {
                errors.push({
                    item: element.field,
                    uuid: element.uuid,
                    field: element.alias,
                    error: result.replace('%alias%', element.alias)
                });

                if (element.quill !== null) {
                    element.field.addClass('is-danger');
                } else {
                    if (element.input[0].type == "select-one" || element.input[0].type == "select-multiple") {
                        element.input.parent().addClass('is-danger');
                    } else {
                        element.input.addClass('is-danger');
                    }
                }

                element.icon.find('i').addClass(this.settings.icon.error);
                element.message.text(result.replace('%alias%', element.alias));
            }

        });
        return errors;
    }

    validation_complete(data, stay) {
        this.onSend(data, stay);
    }

    send(data, stay) {
        data.next = stay;
        let _self = this;
        axios.post(this.action, data).then(function (response) {
            _self.loading = false;
            _self.submit_button.removeClass('is-loading');
            $('.wo-form-button-back').removeClass('is-loading');
            $(_self.submit_button_extra).forEach((el) => {
                $(el).removeClass('is-loading');
            });
            _self.complete(response);
        }).catch(function (error) {
            console.log(error);
        });
    }

    complete(response) {
        if (response.data.status == 'OK') {
            if (this.uploaders.length === 0) {
                this.end(response);
            } else {
                let ups = this.uploaders.map((upldr) => {
                    return upldr.upload(response, this.end);
                });

                Promise.all(ups)
                    .then((results) => this.end(response))
                    .catch((err) => console.log(err));
            }
        } else {
            for (let i in response.data.errors) {
                for (let b in this.elements) {
                    if (this.elements[b].input.attr('name') == i) {
                        if (this.elements[b].input[0].type == "select-one" || this.elements[b].input[0].type == "select-multiple") {
                            this.elements[b].input.parent().addClass('is-danger');
                        } else {
                            this.elements[b].input.addClass('is-danger');
                        }

                        this.elements[b].icon.find('i').addClass(this.settings.icon.error);
                        this.elements[b].message.text(response.data.errors[i]);
                    }
                }
            }
        }
    }

    end(response) {
        if (typeof this.return_path == "object") {
            window.dispatchEvent(this.return_path);
        } else {
            if (this.next_action != null) {
                if (this.next_action == 'stay') {
                    this.submit_button.removeClass('is-loading');
                    this.submit_button.addClass('is-hidden');
                    $(".wo-form-button-back").removeClass('is-hidden');
                    $(".wo-form-button-back").attr("href", (this.return_path !== undefined || this.return_path !== '') ? this.return_path : response.data.return_path)
                    $(this.submit_button_extra).forEach((el) => {
                        $(el).removeClass('is-loading');
                    });
                } else {
                    // console.log(this.next_action);
                    // this.next_action = this.next_action.replace('xx', response.data.id);
                    window.location = response.data.next;
                }
            } else {
                window.location = (this.return_path !== undefined || this.return_path !== '') ? this.return_path : response.data.return_path;
            }
        }
    }

    format_data() {
        this.form.find('.field[data-validation*="NUM"] input').map((el,index)=>{
            $(el).val($(el).val() || 0);
        });

        return serialize(this.form[0], {hash: true, empty: true});
    }

    inputChange(ev) {
        if (ev.target.type == "select-one" || ev.target.type == "select-multiple") {
            $(ev.target).parent().removeClass('is-success').removeClass('is-danger');
        } else {
            $(ev.target).removeClass('is-success').removeClass('is-danger');
        }

        $('[data-woid="' + $(ev.target).data('woid') + '"]').find('span.icon, div.icon').children('i').removeClass(this.settings.icon.success).removeClass(this.settings.icon.error);
        $('[data-woid="' + $(ev.target).data('woid') + '"]').children('p.help').text('');
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
                            window.location = (_self.return_path !== undefined || _self.return_path !== '') ? _self.return_path : response.data.return_path;
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
                                                window.location = (_self.return_path !== undefined || _self.return_path !== '') ? _self.return_path : response.data.return_path;
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
                //window.location = (_self.return_path !== undefined || _self.return_path !== '') ? _self.return_path : response.data.return_path;
            }
        }, (dis) => {
            //cancel
        });
    }

    static set onValidationComplete(nv) {
        this.onValidationComplete = nv;
    }

    static set onSend(nv) {
        this.onSend = nv;
    }

}
