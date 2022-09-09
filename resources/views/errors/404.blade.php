<!DOCTYPE html>
<html>
    <head>
        <title>404 ERROR</title>

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                background-color: #ffffff !important;
            }
        </style>
        <!-- Styles -->
        <link href="{{ pw2css('sad-page.css') }}" rel="stylesheet">
        <link href="{{ pw2font('OpenSans/stylesheet.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ pw2css('vendor/font-awesome/css/all.css') }}">
    </head>
    <body>
        <div id="page-not-found" class="container">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <img class="content__image" src="{{pw2img('error-pages/chucho.png')}}">
                        <div class="content__body">
                            <h1 class="title">404</h1>
                            <h3 class="subtitle">{{Lang::get('error.404')}}</h3>
                            <p class="message">{{Lang::get('error.404_s')}}</p>
                            <div class="col-12">
                                <div class="row justify-content-center">
                                    <a class="error-button" href="{{ url('/') }}">{{Lang::get('error.return')}} <i class="ml-3 fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
