@extends('page.layout.main')
@section('slide')
@endsection

@section('content')
<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <profile :use-recaptcha="false" previous-url="{{route('page.home')}}" url="{{route('page.auth.profile.save')}}"/>
            </div>
        </div>
    </div>
</section>
@endsection