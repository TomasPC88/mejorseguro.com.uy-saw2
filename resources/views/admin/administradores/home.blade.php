@extends('admin.layout.home')
@section('thead')
    <th>ID</th>
    <th></th>
    <th>Nombre</th>
    <th>Mail</th>
    <th>Creado</th>
@endsection
@section('tbody')
    @foreach($data as $d)
        <tr id="{{ $d->id }}">
            <td>{{ $d->id }}</td>
            <td>
                {{--<figure class="image is-24x24">
                    <img src="{{ $d->first_image['default']->icon }}"
                         data-thumb="{{ $d->first_image['default']->thumb }}"
                    >
                </figure>--}}
            </td>
            <td>{{ $d->name }}</td>
            <td>{{ $d->email }}</td>
            <td><span data-ttip="{{ $d->created_at }}">{{ w2_set_date_format_DMY($d->created_at) }}</span></td>
        </tr>
    @endforeach
@endsection
