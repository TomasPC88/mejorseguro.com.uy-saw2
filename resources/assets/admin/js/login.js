require('./bootstrap');

const w2l = {}

w2l.els = {
    panel: null,
    form: null,
    submit_btn: null,
    email: null,
    pass: null,
    msg: null
}

w2l.step = 1;
w2l.url = '';
w2l.fields = {
    email: null,
    password: null
}

$(document).ready(() => {
    w2l.els.panel = $('.sad-login--panel');
    w2l.els.form = $('.sad-login--panel_form');
    w2l.els.submit_btn = w2l.els.form.find('button[type="submit"]');
    w2l.els.email = w2l.els.form.find('.sad-login--panel_form-email input');
    w2l.els.pass = w2l.els.form.find('.sad-login--panel_form-password input');
    w2l.els.msg = w2l.els.form.find('.sad-login--panel_form-submit span');
    w2l.url = w2l.els.form.attr('action');

    w2l.init();
})


w2l.init = () => {

    w2l.els.form.on('submit', (e) => {
        e.preventDefault();

        w2l.els.email.removeClass('is-danger');
        w2l.els.pass.removeClass('is-danger');
        w2l.els.msg[0].style.display = 'none';

        if( w2l.els.email.val() === undefined
            || _.trim(w2l.els.email.val()) === ''
            || !/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(w2l.els.email.val())
        ){
            w2l.els.email.addClass('is-danger');
            return;
        } else {
            w2l.fields.email = w2l.els.email.val();
        }

        if(w2l.step == 1){
            w2l.els.email[0].blur()
            w2l.els.pass[0].focus()
        }

        if(w2l.step > 1){
            if( w2l.els.pass.val() === undefined || _.trim(w2l.els.pass.val()) === '' ){
                w2l.els.pass.addClass('is-danger');
                return;
            } else {
                w2l.fields.password = w2l.els.pass.val();
            }
        }

        if(w2l.step < 4){
            w2l.els.panel.removeClass('step-'+w2l.step).addClass('step-'+(w2l.step+1));
            w2l.step++;
        }

        if(w2l.fields.email !== null && w2l.fields.password !== null){
            w2l.els.submit_btn.addClass('is-loading')

            axios.post(w2l.url, {
                email       : w2l.fields.email,
                password    : w2l.fields.password
            }).then((r) => {
                if(r.data.error !== ''){
                    w2l.els.submit_btn.removeClass('is-loading')
                    w2l.els.panel.removeClass('step-3')
                    w2l.step = 1
                    w2l.els.email[0].focus()
                    w2l.els.msg[0].style.display = ''
                    w2l.fields.email = null
                    w2l.fields.password = null
                    w2l.els.pass.val('')
                    return;
                }
                window.location = r.data.route;
            });
        }

    })

    w2l.els.email[0].focus()
}
