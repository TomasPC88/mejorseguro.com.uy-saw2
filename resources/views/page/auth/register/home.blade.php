@extends('page.layout.main')
@section('slide')
@endsection

@section('content')
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <register
                    url="{{route('page.auth.signup')}}"
                    :use-recaptcha="true"
                    recaptcha-key="{{cache('config')->recaptcha_public}}"
                    forgot-url="{{route('page.user.login.forgot_password')}}"
                >
                </register>
            </div>
        </div>
    </div>
</section>
@endsection