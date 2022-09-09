@extends('admin.layout.home')
@section('thead')
    <th data-col="id">ID</th>
    <th></th>
    <th data-col="name">Nombre</th>
    <th data-col="description">Descripción</th>
    <th data-col="url">URL</th>
    <th data-col="target">Abrir en otra Pág.</th>
    <th data-col="created_at">Creado</th>
@endsection
@section('tbody')
    @foreach($data as $d)
        <tr id="{{ $d->id }}" data-pos="{{ $d->pos }}">
            <td>{{ $d->id }}</td>
            <td>
                <figure class="image is-24x24">
                    <img src="{{ $d->first_image->get('icon') }}"
                         data-thumb="{{ $d->first_image->get('thumb') }}"
                    >
                </figure>
            </td>
            <td>{{ $d->defaultTranslation->name }}</td>
            <td>{{ Str::limit($d->defaultTranslation->description, 50) }}</td>
            <td>{{ Str::limit($d->url, 50) }}</td>
            <td>{!! w2_sino($d->target, true, true) !!}</td>
            <td><span data-ttip="{{ $d->created_at }}">{{ w2_set_date_format_DMY($d->created_at) }}</span></td>
        </tr>
    @endforeach
@endsection
