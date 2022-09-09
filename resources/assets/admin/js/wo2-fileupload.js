import Wo2Utils from "./wo2-utils.js";
import Sortable from "sortablejs";

export default class Wo2Fileupload {
    constructor(element, submit_url, initialData = [],uuid=null, { alias='Galeria', limit=0, type='all', group='normal', maxwidth=0, maxheight=0, video=0} = {})
    {
        this.uuid           = uuid?uuid:Wo2Utils.uuid();
        this.element        = $(element);
        this.submit_url     = submit_url+'/media/';
        this.ALIAS          = alias;
        this.LIMIT          = +limit;
        this.TOTAL          = 0;
        this.TYPE           = type;
        this.GROUP          = group;
        this.MAX_WIDTH      = +maxwidth;
        this.MAX_HEIGHT     = +maxheight;
        this.VIDEO          = Boolean(+video);
        this.holder         = $('<div class="wo2-fu-holder" style="max-height: 700px; overflow: hidden; overflow-y: auto;"></div>');
        this.input          = this.createInput();
        this.notification   = $(`<div class="notification is-hidden"><button class="delete"></button><div class="notification__message"></div></div>`);
        this.btn_holder     = $(`<div class="field has-addons ignore"><p class="control"></p>${this.VIDEO ? '<p class="control"></p><p class="control"></p><p class="control"></p>' : ''}</div>`);
        this.file_btn       = null;
        this.video_btn      = null;
        this.video_input    = null;
        this.INITIAL_FILES  = initialData;

        this.FILES          = [];
        this.sortable       = null;

        this.buildDOM();
        this.addListeners();
    }

    buildDOM(){
        this.element.children().remove();
        let custom_metas = (this.element.find('custom-metas').length > 0) ? this.element.find('custom-metas').html().replace(/>\s+</g, '><').trim() : null;
        this.element.find('custom-metas').remove();

        let custom_buttons = (this.element.find('custom-buttons').length > 0) ? this.element.find('custom-buttons').html().replace(/>\s+</g, '><').trim() : null;
        this.element.find('custom-buttons').remove();

        this.meta_template  = (custom_metas !== null) ? custom_metas : `<div class="field">
            <label class="label">Titulo</label>
            <div class="control">
                <input name="name" class="input is-small" type="text">
            </div>
        </div><div class="field">
            <label class="label">Descripcion</label>
            <div class="control">
                <input name="description" class="input is-small" type="text">
            </div>
        </div>`;


        this.item_template  = `<article id="%id%" class="media">
                <figure class="media-left">
                    <p class="image is-96x96"></p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p class="wo2-fu-item__description">
                            <strong class="wo2-fu-item__title"></strong> <small class="wo2-fu-item__subtitle"></small>
                            <br>
                            <span class="wo2-fu-item__body"></span>

                            <nav class="level">
                                <div class="level-left">
                                    <button class="wo2-fu-item__edit level-item button is-outlined is-small">
                                        <span style="width: auto;" class="icon is-small"><i class="fa fa-download"></i>&nbsp;Descargar</span>
                                    </button>
                                </div>
                            </nav>
                            <progress class="progress is-small wo2-fu-item__progress is-hidden" value="0" max="100">0%</progress>
                        </p>
                    </div>
                </div>
                <div class="media-right">
                    <button class="delete wo2-fu-item__delete"></button>
                </div>
            </article>`;

        this.svg_template   = `<svg width="30" height="30" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <rect fill="%background%" id="canvas_background" height="32" width="32" y="-1" x="-1"/>
                    <g display="none" overflow="visible" y="0" x="0" height="100%" width="100%" id="canvasGrid">
                        <rect fill="url(#gridpattern)" stroke-width="0" y="0" x="0" height="100%" width="100%"/>
                    </g>
                </g>
                <g>
                    <text opacity="0.5" xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="11" id="svg_1" y="18.263585" x="3.238039" stroke-width="0" stroke="#000" fill="#000000">%text%</text>
                </g>
            </svg>`;


        if ( this.TYPE == "document" ) {
            this.file_btn_template  = `<button class="button is-outlined" ${ this.createFileTip() }>
                    <span class="icon is-small">
                        <i class="fa fa-file"></i>
                    </span>
                </button>`;
        } else {
            this.file_btn_template  = `<button class="button is-outlined" ${ this.createFileTip() }>
                <span class="icon is-small">
                    <i class="fa fa-picture-o"></i>
                    </span>
                </button>`;
        }


        this.video_btn_template = `<button class="button is-outlined" ${ this.createVideoTip() }>
                <span class="icon is-small">
                    <i class="fa fa-video-camera"></i>
                </span>
            </button>`;

        this.video_input_template = `<input class="input is-hidden wo-input-video" type="text" placeholder="Url">`;

        this.video_input_button_template = `<a class="button is-primary is-hidden wo-input-video-button">
                Procesar
            </a>`;

        this.input.css({
            'visibility':'hidden',
            'position':'absolute',
            'top':'-60px',
            'left':'0px',
            'height':'0px',
            'width':'0px'
        });
        this.element.append(this.input);
        this.element.append( $('<label class="label">'+this.ALIAS+'</label>') );

        this.file_btn           = $(this.file_btn_template);
        this.btn_holder.find('p').eq(0).append(this.file_btn);

        if(this.VIDEO){
            this.video_btn          = $(this.video_btn_template);
            this.video_input        = $(this.video_input_template);
            this.video_input_button = $(this.video_input_button_template);
            this.btn_holder.find('p').eq(1).append(this.video_btn);
            this.btn_holder.find('p').eq(2).append(this.video_input);
            this.btn_holder.find('p').eq(3).append(this.video_input_button);
        }

        this.element.append(this.btn_holder);
        this.element.append(this.notification);
        this.element.append(this.holder);
    }

    addListeners()
    {
        let _self = this;

        this.file_btn.on('click', this.openFileDialog.bind(this));
        if(this.VIDEO) {
            this.video_btn.on('click', this.openVideoDialog.bind(this));
            //this.video_input.on('input', this.validateVideo.bind(this));
            this.video_input_button.on('click', this.validateVideo.bind(this));
        }

        this.notification.children('.delete').on('click', this.closeMessage.bind(this));

        this.input.on('change', this.change.bind(this));

        this.sortable = new Sortable(this.holder[0], {
            handle: 'p.image',
            dataIdAttr: 'id',
            onUpdate: _self.sortPosition.bind(this)
        });

        if(this.INITIAL_FILES.length > 0){
            this.addInitial();
        }

    }

    addInitial()
    {
        let old_files = this.INITIAL_FILES.map( (file) => {

            file.uuid = Wo2Utils.uuid();
            file.box = $( this.item_template.replace('%id%', file.uuid) );
            if(file.metas !== ''){
                // TODO ver por que el error del parsing aca
                // file.metas=JSON.parse(file.metas);
            }
            file.box.find('.wo2-fu-item__title').text( (file.metas !== '' && file.metas.hasOwnProperty('name')) ? file.metas.name : file.url );
            file.box.find('.wo2-fu-item__body').text( (file.metas !== '' && file.metas.hasOwnProperty('description')) ? file.metas.description : '' );
            // file.box.find('.wo2-fu-item__subtitle').text( Wo2Utils.formatBytes(file.size) );
            file.simple_type = file.type;
            file.ext = file.extension;
            file.edit = file.box.find('.wo2-fu-item__edit');
            file.delete = file.box.find('.wo2-fu-item__delete');
            file.uploaded = true;
            file.updated_metas = true;
            file.updated_pos = true;
            file.row_id = file.id;

            this._addItemEvents(file);
            if(file.type == 'document' || file.type == 'sound'){
                file.file_type = _.filter(TYPES, {type: file.type})[0];
                this._thumbnail(file);
            }
            if(file.type == 'video'){
                file.box.find('p.image').css({
                    'backgroundImage':"url('"+file.video_thumbnail+"')",
                    'backgroundSize':"cover",
                    'backgroundPosition':"center center"
                });
            }
            if(file.type == 'image'){
                let img = '<img style="width: 100; height: 100%; object-fit: scale-down;" src="'+ file.thumb +'" data-thumb="'+ file.normal +'">';
                file.box.find('p.image').append(img);
                // file.box.find('p.image').css({
                //     'backgroundImage':"url('"+file.thumb+"')",
                //     'backgroundSize':"cover",
                //     'backgroundPosition':"center center"
                // });
            }
            this.holder.append( file.box );

            return file;
        });

        this.FILES.push(...old_files);
        this.TOTAL = this.FILES.length;

    }

    sortPosition()
    {
        _.forEach(this.sortable.toArray(), (v, k) => {
            let file_index = _.findIndex(this.FILES, {uuid: v} );
            this.FILES[file_index].updated_pos = ( this.FILES[file_index].pos === k);
            this.FILES[file_index].pos = k;
        });
    }

    createInput()
    {
        let template = $('<input id="'+Wo2Utils.uuid()+'" type="file">');
        if(this.LIMIT === 0 || this.LIMIT > 1){
            template.attr('multiple', 'multiple');
        }
        if(this.TYPE == 'all'){
            template.attr('accept', "application/*,text/*,audio/*,image/*");
        } else if(this.TYPE == 'image'){
            template.attr('accept', "image/*");
        } else if(this.TYPE == 'document'){
            template.attr('accept', "application/*,text/*");
        } else if(this.TYPE == 'audio'){
            template.attr('accept', "audio/*");
        }

        return template;
    }

    createFileTip()
    {
        let amount = this.LIMIT;
        let type = '';
        if(this.TYPE == 'all'){
            type = 'archivo'+(amount !== 1 ? 's' : '');
        } else if(this.TYPE == 'image'){
            type = 'imagen'+(amount !== 1 ? 'es' : '');
        } else if(this.TYPE == 'document'){
            type = 'documento'+(amount !== 1 ? 's' : '');
        } else if(this.TYPE == 'audio'){
            type = 'audio'+(amount !== 1 ? 's' : '');
        }
        let tip = `data-ttip="Seleccionar ${amount !== 0 ? amount+' ' : ''}${type}"`;

        return tip;
    }

    createVideoTip()
    {
        let amount = this.LIMIT;
        let tip = `data-ttip="Seleccionar ${amount !== 0 ? amount+' ' : ''}video${amount !== 1 ? 's' : ''} de Youtube o Vimeo"`;

        return tip;
    }

    openFileDialog(ev)
    {
        ev.preventDefault();
        $(ev.target).closest('button')[0].blur();
        this.input.trigger('click');
    }

    openVideoDialog(ev)
    {
        ev.preventDefault();
        $(ev.target).closest('button')[0].blur();

        if( this.LIMIT !== 0 && (this.TOTAL + 1) > this.LIMIT ) {
            this.Message('Sólo puedes subir un máximo de '+this.LIMIT+' imagen(es) o video(s)', 'danger');
            this.input.val('');
            return;
        }

        $('.wo-input-video').removeClass('is-hidden');
        $('.wo-input-video-button').removeClass('is-hidden');
    }

    validateVideo()
    {
        $('.wo-input-video-button').addClass('is-loading');
        let url = $('.wo-input-video').val();
        $('.wo-input-video').val('');
        let _self = this;
        let regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        let match = regExp.exec(url);
        if (match && match[2].length == 11) {
            let thumbnail = "http://i.ytimg.com/vi/" + match[2] + "/maxresdefault.jpg";
            let videoInfo = {
                uuid: Wo2Utils.uuid(),
                video_id: match[2],
                url: url,
                thumbnail: thumbnail,
                platform: 'YT',
                simple_type: 'video',
            };
            _self.addVideoInView(videoInfo);
            $('.wo-input-video-button').removeClass('is-loading');
        }else {
            let regExpVimeo = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
            let matchVimeo = regExpVimeo.exec(url);
            axios({
                method:'get',
                url: 'https://vimeo.com/api/oembed.json?url=' + url,
            })
            .then(function(response){
                let videoInfo = {
                    uuid: Wo2Utils.uuid(),
                    video_id: matchVimeo[3],
                    url: url,
                    thumbnail: response.data.thumbnail_url,
                    platform: 'VM',
                    simple_type: 'video',
                };
                _self.addVideoInView(videoInfo);
                $('.wo-input-video-button').removeClass('is-loading');
            })
            .catch(function (res) {
                if(res instanceof Error) {
                    _self.Message('No es un URL válido', 'danger');
                    let videoInfo = {};
                    _self.addVideoInView(videoInfo);
                    $('.wo-input-video-button').removeClass('is-loading');
                } else {
                    _self.Message('No es un URL válido', 'danger');
                    let videoInfo = {};
                    _self.addVideoInView(videoInfo);
                    $('.wo-input-video-button').removeClass('is-loading');
                }
            });
        }
    }

    /*
    *
    * Message
    * type = primary |info | success | warning | danger
    *
    */

    Message(msg = '', type = '')
    {
        this.notification.children('.notification__message').html(msg);
        this.notification.removeClass('is-primary is-info is-success is-warning is-danger');
        if(type !== '') this.notification.addClass('is-'+type);

        this.showMessage();
    }

    closeMessage(ev)
    {
        ev.preventDefault();
        $(ev.target).closest('button')[0].blur();

        this.hideMessage();
    }

    showMessage()
    {
        if(this.notification.hasClass('is-hidden')) this.notification.removeClass('is-hidden');
    }

    hideMessage()
    {
        if(!this.notification.hasClass('is-hidden')) this.notification.addClass('is-hidden');
    }

    change(ev)
    {
        let selected = this.input[0].files.length;

        if( this.LIMIT !== 0 && (this.TOTAL + selected) > this.LIMIT ) {
            this.Message('Sólo puedes subir un máximo de '+this.LIMIT+' imagen(es) o video(s)', 'danger');
            this.input.val('');
            return;
        }

        let new_files = Array.from(this.input[0].files)
            .filter((file) => {
                file.file_type = _.filter(TYPES, (o) => o.regex.test(file.type) );
                return file.file_type.length > 0;
            })
            .map((file) => {

                file.uuid = Wo2Utils.uuid();
                file.box = $( this.item_template.replace('%id%', file.uuid) );
                file.box.find('.wo2-fu-item__title').text( file.name );
                file.box.find('.wo2-fu-item__subtitle').text( Wo2Utils.formatBytes(file.size) );
                file.file_type = file.file_type[0];
                file.simple_type = file.file_type.type;
                file.ext = /\.([0-9A-z]+)$/.exec(file.name)[1];
                file.edit = file.box.find('.wo2-fu-item__edit');
                file.delete = file.box.find('.wo2-fu-item__delete');
                file.pos = 0;
                file.metas = {};
                file.uploaded = false;
                file.updated_metas = false;
                file.updated_pos = false;

                this._addItemEvents(file);
                this._thumbnail(file);
                this.holder.append( file.box );

                if(file.size > 2000000 && file.simple_type == 'image' ) {
                    this.bigImage(file);
                }

                return file;
        });
        this.FILES.push(...new_files);
        this.TOTAL = this.FILES.length;
        this.input.val('');
        this.sortPosition();
    }

    // bigImage(file)
    // {
    //     file.box.addClass('is-loading');
    //     file.box.find('.wo2-fu-item__title').text( "Procesando imagen..." );
    //
    //     let new_file = null;
    //     let image = new Image();
    //     // let canvas = new Canvas();
    //
    //     let reader = new FileReader();
    //     reader.onloadend = () => {
    //         image.src = reader.result;
    //         console.log(image);
    //         console.log('w/h', image.width+'/'+image.height);
    //
    //
    //
    //         // pica.resize(original)
    //
    //         // Jimp.read(original)
    //         //     .then((img) => {
    //         //
    //         //         img.scaleToFit(1920,1080).quality(80).getBuffer(file.type, (err, buff) => {
    //         //             new_file = new File([buff], file.name, {
    //         //                 type: file.type
    //         //             });
    //         //
    //         //
    //         //             new_file.uuid = file.uuid;
    //         //             new_file.box = file.box;
    //         //             new_file.file_type = file.file_type;
    //         //             new_file.simple_type = file.simple_type;
    //         //             new_file.ext = file.ext;
    //         //             new_file.edit = file.edit;
    //         //             new_file.delete = file.delete;
    //         //             new_file.pos = file.pos;
    //         //             new_file.metas = file.metas;
    //         //             new_file.uploaded = file.uploaded;
    //         //             new_file.updated_metas = file.updated_metas;
    //         //             new_file.updated_pos = file.updated_pos;
    //         //
    //         //             let indx = _.findIndex(this.FILES, {uuid:file.uuid});
    //         //             this.FILES[indx] = new_file;
    //         //             new_file.box.find('.wo2-fu-item__title').text( new_file.name );
    //         //             new_file.box.find('.wo2-fu-item__subtitle').text( Wo2Utils.formatBytes(new_file.size) );
    //         //             new_file.box.removeClass('is-loading');
    //         //
    //         //         });
    //         //     });
    //
    //     }
    //     reader.readAsDataURL(file);
    //
    // }

    bigImage(file)
    {
        file.box.addClass('is-loading');
        file.box.find('.wo2-fu-item__title').text( "Procesando imagen..." );

        let new_file = null;

        let reader = new FileReader();
        reader.onloadend = () => {
            const original = reader.result;
            Jimp.read(original)
                .then((img) => {

                    img.scaleToFit(1920,1080).quality(80).getBuffer(file.type, (err, buff) => {
                        new_file = new File([buff], file.name, {
                            type: file.type
                        });


                        new_file.uuid = file.uuid;
                        new_file.box = file.box;
                        new_file.file_type = file.file_type;
                        new_file.simple_type = file.simple_type;
                        new_file.ext = file.ext;
                        new_file.edit = file.edit;
                        new_file.delete = file.delete;
                        new_file.pos = file.pos;
                        new_file.metas = file.metas;
                        new_file.uploaded = file.uploaded;
                        new_file.updated_metas = file.updated_metas;
                        new_file.updated_pos = file.updated_pos;

                        let indx = _.findIndex(this.FILES, {uuid:file.uuid});
                        this.FILES[indx] = new_file;
                        new_file.box.find('.wo2-fu-item__title').text( new_file.name );
                        new_file.box.find('.wo2-fu-item__subtitle').text( Wo2Utils.formatBytes(new_file.size) );
                        new_file.box.removeClass('is-loading');

                    });
                });

        }
        reader.readAsArrayBuffer(file);

    }

    addVideoInView(urlInfo)
    {
        $('.wo-input-video').addClass('is-hidden');
        $('.wo-input-video-button').addClass('is-hidden');

        let video = urlInfo;
        if (!_.isEmpty(video)) {
            video.box = $( this.item_template.replace('%id%', video.uuid) );
            video.box.find('.wo2-fu-item__title').text( video.url );
            video.box.find('.wo2-fu-item__subtitle').text( video.platform );
            video.edit = video.box.find('.wo2-fu-item__edit');
            video.delete = video.box.find('.wo2-fu-item__delete');
            video.pos = 0;
            video.metas = {};
            video.uploaded = false;
            video.updated_metas = false;
            video.updated_pos = false;

            this._addItemEvents(video);
            this._thumbnail_video(video);
            this.holder.append(video.box);
            this.FILES.push(video);
            this.sortPosition();
        }
    }

    _addItemEvents(item)
    {
        item.edit.attr('parent-id', item.uuid);
        item.edit.on('click', this.downloadFile.bind(this));

        item.delete.attr('parent-id', item.uuid);
        item.delete.on('click', this.deleteFile.bind(this));
    }

    _thumbnail(file)
    {
        if(file.file_type.type == 'image') {
            this._thumbnail_image(file);
        } else if(file.file_type.type == 'document' || file.file_type.type == 'audio') {
            this._thumbnail_document_audio(file);
        }
    }

    _thumbnail_image(file)
    {
        let reader = new FileReader();
        reader.onloadend = () => {
            let img = '<img style="width: 100; height: 100%; object-fit: scale-down;" src="'+ reader.result +'" data-thumb="'+ reader.result +'">';
            file.box.find('p.image').append(img);
            file.box.find('nav').empty();
        }
        reader.readAsDataURL(file);
    }

    _thumbnail_video(file){
        file.box.find('p.image').css({
            'backgroundImage':"url('"+file.thumbnail+"')",
            'backgroundSize':"cover",
            'backgroundPosition':"center center"
        });
    }

    _thumbnail_document_audio(file)
    {
        let svg = this.svg_template.replace('%text%', file.ext.toUpperCase()).replace('%background%', file.file_type.color);

        file.box.find('p.image').css({
            'backgroundImage':"url('data:image/svg+xml;base64,"+btoa(svg)+"')",
            'backgroundSize':"cover",
            'backgroundPosition':"center center"
        });

    }

    deleteFile(ev)
    {
        ev.preventDefault();
        let button  = $(ev.target).closest('button');
        let _parent = button.attr('parent-id');
        let file_index = _.findIndex(this.FILES, {uuid: _parent} );
        let _self = this;

        button[0].blur();

        if( this.FILES[file_index].uploaded ) {
            axios.delete(this.submit_url+this.FILES[file_index].row_id, { params: {uuid: _parent }})
                .then((r) => {
                    let file_index = _.findIndex(_self.FILES, {uuid: r.data.uuid} );
                    this.holder.find('#'+r.data.uuid).remove();
                    _.pullAt(this.FILES, file_index);
                    this.TOTAL = this.FILES.length;

                }).catch((err) => {
                    console.log(err);
                });
        } else {
            this.holder.find('#'+_parent).remove();
            _.pullAt(this.FILES, file_index);
            this.TOTAL = this.FILES.length;
        }
    }

    downloadFile(ev)
    {
        ev.preventDefault();
        let button  = $(ev.target).closest('button');
        let _parent = button.attr('parent-id');
        let file_index = _.findIndex(this.FILES, {uuid: _parent} );

        button[0].blur();

        if(file_index > -1) {
            var link = document.createElement('a');
            link.href = this.FILES[file_index].large;
            link.download = this.FILES[file_index].url;
            document.body.appendChild(link);
            link.click();
        }
    }

    upload(form_response)
    {
        let formResponse = form_response.data;
        let _self = this;
        return new Promise((resolve, reject) => {
            let to_upload = _.filter(this.FILES, (o) => !o.uploaded  || !o.updated_metas || !o.updated_pos );

            let post_proms = to_upload.map((file) => {

                let formData = new FormData();
                if(file.simple_type == 'image' || file.simple_type == 'document') {
                    if(!file.uploaded) formData.append('file', file);
                }

                if(file.simple_type == 'video') {
                    if(!file.uploaded) {
                        formData.append('video[platform]', file.platform);
                        formData.append('video[id]', file.video_id);
                        formData.append('video[url]', file.url);
                        formData.append('video[thumbnail]', file.thumbnail);
                    }
                }

                formData.append('uuid', file.uuid);
                formData.append('simple_type', file.simple_type);
                formData.append('pos', file.pos);

                formData.append('group', _self.GROUP);

                if(file.uploaded) {
                    if(!file.updated_metas || !file.updated_pos){
                        formData.append('row_id', file.row_id);
                    }
                }

                _.forEach(file.metas, (v, k) => {
                    formData.append(`metas[${k}]`, v);
                });


                let config = {
                    onUploadProgress: (progressEvent) => {
                        let percent = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        if(percent > 1) file.box.find('.wo2-fu-item__progress').removeClass('is-hidden');
                        file.box.find('.wo2-fu-item__progress').attr('value', percent);
                    },
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                return axios.post(this.submit_url+formResponse.data.id, formData, config)
                            .then((res) => {
                                let file_index = _.findIndex(_self.FILES, {uuid: res.data.uuid} );

                                let name = res.data.data;
                                let desc = '';

                                if(_.size(_self.FILES[file_index].metas) > 0){
                                    name = _self.FILES[file_index].metas.name;
                                    desc = _self.FILES[file_index].metas.description;
                                }

                                _self.FILES[file_index].uploaded = true;
                                _self.FILES[file_index].updated_metas = true;
                                _self.FILES[file_index].updated_pos = true;
                                _self.FILES[file_index].row_id = res.data.id;
                                _self.FILES[file_index].box.find('.wo2-fu-item__title').text(name);
                                _self.FILES[file_index].box.find('.wo2-fu-item__body').text(desc);
                            })
                            .catch((err) => {
                                reject(err);
                            });
            });

            if(post_proms.length === 0){
                resolve(form_response);
            } else {
                axios.all(post_proms).then(() => {
                    console.log("All submited!");
                    resolve(form_response);
                });
            }
        });
    }
}

export const TYPES = [
    { type: 'image', color: '#F46AFF', regex: /^image\/(jpeg|gif|png|tiff|webp|bmp)$/ },
    { type: 'document', color: '#6AECFF', regex: /^application\/(pdf|plain|xml|msword|excel|x-excel|(vnd.(ms|open)))/ },
    { type: 'document', color: '#6AECFF', regex: /^text\/(plain|xml)$/ },
    { type: 'audio', color: '#FFD66A', regex: /^audio\/(mp4|ogg|mp3|mpeg3|x-mpeg3|wav|x-wav|midi|x-midi)/ }
];
