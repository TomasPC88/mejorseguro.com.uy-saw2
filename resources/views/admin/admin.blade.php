<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('wo2.app.name', 'SaW2') }}</title>

    <!-- Styles -->
    <link href="{{ w2css('sad.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ w2img('sitios_logo/iso/sitios-iso_c_16.png') }}">
    <script>
        var wo2_settings = {!! json_encode(config('wo2.app')) !!};
        var wo2_base = "{{ url('admin') }}/";
    </script>
</head>
<body>

    <div class="pageloader is-active">
        <div class="sitios-logo">
            <figure></figure>
        </div>
        <span class="title">Cargando</span>
    </div>

    <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
        @component('admin.components.nav', compact('title','name'))
        @endcomponent
    </nav>
    <div class="wo2_searchList is-hidden notification is-primary"></div>

    <aside id="menu" class="menu">
        @component('admin.components.menu', ['section'=>$name])
        @endcomponent
    </aside>

    <section id="overlay"></section>

    @yield('wo-custom-content')

    <section class="wrapper">
        <div class="ajax-container"></div>
        <div class="container is-fluid">
            @yield('content')
        </div>
    </section>


    <footer class="footer">
        <div class="container is-fluid">
            @component('admin.components.footer')
            @endcomponent
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ w2js('domtastic.js') }}"></script>
    <script src="{{ w2js('wo2-app.js') }}"></script>
    <script src="{{ w2js('jimp.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
