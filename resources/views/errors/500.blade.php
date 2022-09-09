<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>


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
        <div id="internal-server-error"class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <img class="content__image" src="{{pw2img('error-pages/bombillo.png')}}">
                    <div class="content__body">
                        <h1 class="title">500</h1>
                        <h3 class="subtitle">{{Lang::get('errors')}}</h3>
                        <p class="message">{{Lang::get('error.500_s')}}</p>
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
