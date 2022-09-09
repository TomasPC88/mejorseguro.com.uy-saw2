<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('wo2.app.name', 'Laravel') }}</title>

    <!-- Styles -->
{{--    <link href="{{ w2css('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">--}}
    <link href="{{ w2css('sad.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ w2img('sitios_logo/iso/sitios-iso_c_16.png') }}">
</head>
<body>

    <section class="hero is-fullheight sad-login">
        <div class="hero-body">
            <div class="container has-text-centered">

                <div class="columns">
                    <div class="column is-12">
                        <div class="sad-login--panel">
                            <form class="sad-login--panel_form" method="post" action="{{ route('admin.login.submit') }}">
                                {{ csrf_field() }}
                                <div class="sad-login--panel_form-header">
                                    <figure class="image is-96x96 sad-login--panel_header">
                                        <img src="{{ w2img('sitios_logo/iso/sitios-iso_c_256.png') }}">
                                    </figure>
                                    <h1 class="title">sitios <span>AW2</span></h1>
                                </div>
                                <div class="field sad-login--panel_form-email">
                                    <p class="control has-icons-left">
                                        <input name="email" value="{{ old('email') }}" class="input" type="text" placeholder="Email">
                                        <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                    </p>
                                </div>
                                <div class="field sad-login--panel_form-password">
                                    <p class="control has-icons-left">
                                        <input name="password" class="input" type="password" placeholder="Contraseña">
                                        <span class="icon is-small is-left"><i class="fa fa-lock"></i></span>
                                    </p>
                                </div>
                                <div class="field sad-login--panel_form-submit">
                                    <p class="control">
                                        <span style="display:none;">Email y/o contraseña<br>son incorrectos</span>
                                        <button type="submit" class="button is-success">Siguiente</button>
                                    </p>
                                </div>

                                <input style="display:none;" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>

                                <div class="sad-login--panel_form-forgot content is-small">
                                    Sitios AW2 v0.0.195
                                    {{-- <a href="{{ route('admin.password.request') }}">Olvide mi contraseña</a> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Scripts -->
    <script src="{{ w2js('domtastic.js') }}"></script>
    <script src="{{ w2js('login.js') }}"></script>
</body>
</html>
