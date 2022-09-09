@php
    $items = explode('/',URL::current());
    $items= array_splice($items,3,3);
    $path='';
@endphp
<ul class="breadcrumb">
    <li><a href="{{ url('/') }}">{{__('messages.inicio')}}</a></li>
    @foreach($items as $name)
        @php($path.="/$name")
        <li><i class="fas fa-angle-right"></i></li>
        <li><a href="{{URL::to('/')."$path"}}">{{ $loop->last?preg_replace("/(\-)+|\d*$/", " ", __("messages.$name")):__("messages.$name") }}</a></li>
    @endforeach
</ul>
