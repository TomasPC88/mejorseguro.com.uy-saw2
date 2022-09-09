@extends('admin.layout.home')
@section('exportar')
    @if($data->where('active',1)->count() > 0)
        <a href="{{route('admin.newsletters.csv')}}?key=email" class="button is-outlined row-option"><i class="mdi mdi-download"></i>CSV</a>
    @endif
@endsection
@section('thead')
    <th data-col="id">ID</th>
    <th data-col="email">Email</th>
    <th data-col="active">Activo</th>
@endsection
@section('tbody')
    @foreach($data as $d)
        <tr id="{{ $d->id }}" data-pos="{{ $d->pos }}">
            <td>{{ $d->id }}</td>        
            <td>{{ $d->email }}</td>
            <td>{!! w2_sino($d->active, true, true) !!}</td>
        </tr>
    @endforeach
@endsection
