@extends('page.layout.main')
@section('slide')
@endsection

@section('content')
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <login
                    url="{{route('page.auth.signin')}}"
                    :use-recaptcha="false"
                    forgot-url="{{route('page.auth.forgot_password')}}"
                    previous-url="{{url()->previous()}}"
                >
                </login>
            </div>
        </div>
    </div>
</section>
@endsection