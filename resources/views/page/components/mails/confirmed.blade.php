@component('mail::message')
<div class="mail__item mail__item--confirmed">
    <div class="item__logo">
        <div class="item__image">
            <img src="{{pw2img('mail-notifications/user_reg.png')}}" alt="">
        </div>
    </div>
    <div class="item__body">
        <div class="item__body--content">
            <p>Gracias por confirmar su suscripción en <a href="{{route('page.home')}}">{{env('APP_NAME')}}</a>, podrá
                mantanerse
                actualizado de todas nuestras novedades.</p>
        </div>
    </div>
</div>
{{-- <div style="width: 100%; margin-bottom:100px; text-align: center;">
    <img style="margin: 0 auto; height:90px; width:auto;" src="{{ pw2img('logo-valagu-footer.png') }}" alt="">
</div>
<p>Gracias por confirmar su suscripción en <a href="{{route('page.home')}}">{{env('APP_NAME')}}</a>, podrá mantanerse
    actualizado de todas nuestras novedades.</p> --}}
@endcomponent