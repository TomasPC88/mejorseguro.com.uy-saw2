@extends('errors.layout')

@section('content')
  <div id="mantenimient-mode"class="container">
    <div class="row mb-4">
      <div class="col-12">
        <div class="icon-box">
          <i class="fa fa-wrench" aria-hidden="true"></i>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <h1>{{ __('error.503_title') }}</h1>
        <p>{{ __('error.503_desc') }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <ul class="info-ls">
          <li>
            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              Edif Mariscomar local 02 calle 24 esq 19 Punta del Este - Uruguay
            </a>
          </li>
          <li>
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              42 448 742
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="row call2action-box">
      <div class="col-12 call2action">
        <button class="send btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseform" aria-expanded="false" aria-controls="collapseExample">
          Enviar un mensaje
        </button>

      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="collapse" id="collapseform">
          <div class="card card-body">
            <contact url="{{route('page.contacto.send')}}" :use-recaptcha="true"
                     :recaptcha-key="'{{ env('GOOGLE_RECAPTCHA_KEY') }}'">
            </contact>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
  document.addEventListener('click', function (event) {

    if (event.target.matches('.send')) {
      let fadeTarget = document.querySelector('.call2action-box');

      let fadeEffect = setInterval(function () {
        if (!fadeTarget.style.opacity) {
          fadeTarget.style.opacity = 1;
        }
        if (fadeTarget.style.opacity > 0) {
          fadeTarget.style.opacity -= 0.1;
        } else {
          clearInterval(fadeEffect);
        }
      }, 15);

      fadeTarget.style.display = 'none';
    };
  }, false);


</script>
@endpush
