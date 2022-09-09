<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="57x57" href="{{ pw2img('favicons/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ pw2img('favicons/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ pw2img('favicons/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ pw2img('favicons/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ pw2img('favicons/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ pw2img('favicons/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ pw2img('favicons/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ pw2img('favicons/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ pw2img('favicons/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{ pw2img('favicons/android-icon-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ pw2img('favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ pw2img('favicons/favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ pw2img('favicons/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ pw2img('favicons/manifest.json') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="image/favicons/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{!! csrf_token() !!}">
  <link rel="stylesheet" href="{{ pw2css('vendor/font-awesome/css/fontawesome-all.min.css') }}">
  <link rel="stylesheet" href="{{ pw2fonts('poppins/stylesheet.css') }}">
  <link rel="stylesheet" href="{{ pw2css('vendor/nouislider.min.css') }}">
  <link rel="stylesheet" href="{{ pw2css('sad-errors.css') }}">
  <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
  </script>
  @stack('styles')
  <title>{{ env('APP_NAME') }}</title>
  <script>
    window.locale = '{{app()->getLocale()}}';
    window.locales = {!! json_encode(explode(',',config('app.locales'))) !!};
    window.currency = '{{Session::get('currency')}}';
  </script>
</head>

<body>
<div id="vue" class="error-page">
  <header>
    <lang-selector :langs="[{es:'ES'},{uk:'UK'},{pt:'PT'}]"></lang-selector>
  </header>
  <main role="main">
    @yield('content')
  </main>
{{--  @include('page.layout.includes.footer.footer')--}}
</div>
<script src="{{ pw2js('sad-page.js') }}"></script>
<script src="{{ pw2js('vue-app.js') }}"></script>
@stack('scripts')
</body>

</html>