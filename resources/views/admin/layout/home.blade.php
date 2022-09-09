@extends('admin.admin')
@section('content')
    <div class="columns">
        <div class="column wo2-table-options">
            @can("actualizar-{$name}")
                <a href="{{url("admin/$name/cambiar/active/%id%")}}" class="button is-primary is-outlined is-hidden row-option-hidden wo2-table-options__toggle">
                    <span class="icon is-small"><i class="fa fa-eye fa-1x"></i></span>
                </a>
               @yield('toggles')
            @endcan
            @if(!isset($nuevo))
                    @can("nuevo-{$name}")
                        <a href="{{url("admin/$name/nuevo")}}" class="button is-primary row-option">Nuevo</a>
                    @endcan
                @endif
            @can("editar-{$name}")
              <a href="{{url("admin/$name/%id%/editar")}}" class="button is-outlined is-hidden row-option-hidden">Editar</a>
            @endcan
            @can("borrar-{$name}")
                <a href="{{url("admin/$name/%id%")}}" class="button is-danger is-outlined is-hidden row-option-hidden wo2-table-options__delete wo2-not-show-loading">
                    <span class="icon is-small"><i class="fa fa-trash"></i></span>
                </a>
            @endcan
                @yield('exportar')
            @can("actualizar-{$name}")
                @yield('actions')
            @endcan

        </div>
        <div class="column is-one-third">
            <div class="field">
                <p class="control has-icons-left has-icons-right">
                    <input class="input wo2-table-search" type="text" placeholder="buscar en la lista...">
                    <span class="icon is-small is-left"><i class="fa fa-search"></i></span>
                    <span class="icon is-small is-right"><i class="fa fa-check"></i></span>
                </p>
            </div>
        </div>
    </div>

    <div class="columns wo2-table">
        <div class="column">
            <div class="th-background"></div>
            <div class="table-holder">
                <table class="table">
                    <thead>
                    <tr>
                        @yield('thead')
                    </tr>
                    </thead>

                    <tbody class="drag-container">
                        @yield('tbody')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="columns">
        <div class="column">
            {{ $data->appends(request()->input())->links() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // TODO Inicializar la Tabla cuando se maneje bien el guardado del orden en la jerarquia de modelos
        const wo2_table = new window.Wo2Tables({
            section_path: '{{ url("admin/$name") }}',
            @can("actualizar-{$name}")
                sortable:{{isset($not_drag)?'false':$data->count()>1?'true':'false'}}
            @endcan
        });
    </script>
@endpush
