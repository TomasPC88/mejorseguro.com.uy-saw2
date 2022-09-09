@extends('admin.layout.home')
@section('thead')
    <th data-col="id">ID</th>
    <th data-col="name">Nombre</th>
    <th data-col="name">Permisos</th>
    <th data-col="created_at">Creado</th>
@endsection
@section('tbody')
    @foreach($data as $d)
        <tr id="{{ $d->id }}" data-pos="{{ $d->pos }}">
            <td>{{ $d->id }}</td>
            <td>{{ $d->name }}</td>
            <td>{{  $d->permissions->pluck('name')}}</td>
            <td><span data-ttip="{{ $d->created_at }}">{{ w2_set_date_format_DMY($d->created_at) }}</span></td>
        </tr>
    @endforeach
@endsection
