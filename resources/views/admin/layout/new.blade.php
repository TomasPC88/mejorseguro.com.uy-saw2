@extends('admin.admin')
@section('content')
    <div class="error-list notification is-warning is-hidden">
        <p>Corrija los siguientes errores</p>
        <hr>
        <button class="delete" onclick="this.parentNode.classList.add('is-hidden')"></button>
        <ul>
        </ul>
    </div>
    <div class="columns">
        <div class="column">
            <h1 class="title">{{ $title }}</h1>
        </div>
        <div class="column has-text-right">
            @canany(["crear-{$name}","actualizar-{$name}"])
            <button class="button is-success wo2-form-button">
                <span class="icon is-small"><i class="fa fa-check"></i></span>
                <span>Guardar</span>
            </button>
            @endcanany
                @if(!isset($borrar))
                    @if( isset($data->id))
                        @can("borrar-{$name}")
                            <a href="{{ url("admin/$name/$data->id") }}" class="button is-danger is-outlined wo2-form-delete-button wo2-not-show-loading">
                                <span class="icon is-small"><i class="fa fa-trash"></i></span>
                            </a>
                        @endcan
                    @endif
                @endif
            @canany(["crear-{$name}","actualizar-{$name}"])
                @yield('navbar')
            @endcanany
        </div>
    </div>

    <form id="vue" class="wo2-form" action="{{ $url }}">

        @yield('form_content')

    </form>

    @yield('body_content')
@endsection
@push('scripts')
    <script>
        const wo2_form = new window.Wo2Forms({
            returnPath: '{{ url("admin/$name") }}',
            uploaderData: {!! isset($data->medias) ? json_encode($data->medias) : '[]' !!}
        });
    </script>
@endpush
