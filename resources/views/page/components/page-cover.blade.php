@php
    $items = explode('/',URL::current());
    $items= array_splice($items,3,3);
    $path='';
@endphp
<section class="page-cover" style="background-image: url('{{ pw2img('portada/slide3.jpg')}}')">
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
                    <div class="overlay overlay__content">
                        {{-- Cada página deberá tener un título y una descripción
                         a agregarse desde el Admin. En caso que no las tenga se mostrará
                         el título de la sección, según la ruta
                         --}}
                         <h1> {{__('messages.'.current($items))}} </h1>
{{--                        <h1>{{ __('general.BannerOptions')[0] }} <span class="txt-featured">{{ __('general.BannerOptions')[1] }}</span></h1>--}}
{{--                        <h2>{{ __('general.BannerOptions')[2] }} <span class="txt-featured--bg">{{ __('general.BannerOptions')[3] }}</span></h2>--}}
                        @include('page.components.breadcrumb')
                    </div>
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</section>
