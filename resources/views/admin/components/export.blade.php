<div class="columns">
    <div style="text-align: center" id="alert" class="column notification is-hidden">
    </div>
</div>

<p class="has-text-centered is-size-5 has-text-weight-medium">Enviar Ficha en Formato PDF</p>

<div class="columns">

    <div class="column">
        <div class="field" data-validation="['OPT']" data-alias="Email" data-icon="">
            <div class="control has-icons-right">
                <input name="email" class="input" type="text">
                <a href="{{route('admin.propiedades.pdf.descargar')}}" target="_blank" id="watch"
                   class="is-pulled-right is-hidden" title="Descargar PDF">
                    Vista Previa <i style="padding:10px" class="fa fa-2 fa-download">&nbsp;</i>
                </a>
            </div>
        </div>
        <button id="send" disabled class="button is-success is-pulled-right">
            <span class="icon is-small"><i class="fa fa-send"></i></span>
            <span>Enviar</span>
        </button>
    </div>

</div>
@push('scripts')
    <script>
        const button = document.querySelector('button#send')
        const alert = document.querySelector('div#alert')
        const i = button.querySelector('i')
        const link = document.querySelector('a#watch')
        const email = document.querySelector('[name="email"]')

        email.addEventListener('change', (evt) => {
            if (evt.target.value === '') {
                button.setAttribute('disabled')
                return
            }

            button.removeAttribute('disabled')
        });

        link.addEventListener('click',(evt)=>{
            evt.target.classList.add('is-hidden')
        });

        button.addEventListener('click', (evt) => {
            evt.preventDefault();
            link.classList.add('is-hidden')
            i.className = 'fa fa-spinner fa-spin'
            button.querySelector('span:not(:first-child)').innerText = 'Enviando...'
            axios.get("{{route('admin.propiedades.pdf.enviar',$id)}}", {
                params: {
                    email: document.querySelector('[name="email"]').value
                }
            })
                .then(({data}) => {
                    const {message} = data
                    alert.classList.add(`is-${message.type}`)
                    alert.innerText = message.text
                    alert.classList.remove('is-hidden')
                    link.classList.remove('is-hidden')
                })
                .catch((e) => {
                    console.log(e.response);
                    switch (e.response.status) {
                        case 422:
                            email.parentElement.nextElementSibling.innerText = e.response.data.errors.email
                            alert.classList.add('is-warning')
                            alert.innerText = 'Existen varios errores en el formulario'
                            break
                        default:
                            alert.classList.add('is-danger')
                            alert.innerText = 'Ocurrió un error en el envío del documento'
                    }
                    alert.classList.remove('is-hidden')
                })
                .finally(() => {
                    i.className = 'fa fa-send'
                    button.querySelector('span:not(:first-child)').innerText = 'Enviar'
                    setTimeout(() => {
                        alert.className = 'column notification is-hidden'
                        email.parentElement.nextElementSibling.innerText = ''
                    }, 5000)
                })
        });
    </script>
@endpush
