@extends('page.layout.main')
@section('slide')
@endsection

@section('content')
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <forgot-password 
                :use-recaptcha="true"
                 previous-url="{{route('page.home')}}" 
                 recaptcha-key="{{cache('config')->recaptcha_public}}"            
                 url="{{route('page.auth.forgot-password.recover')}}"
                 />
            </div>
        </div>
    </div>
</section>
@endsection