@extends('admin.layout.new')
@section('form_content')
    <input type="hidden" name="id" value="{{ $data->id??''}}" />
    <div class="columns">

        <div class="column">

            <div class="field" data-validation="['NE']" data-alias="Nombre" data-icon="">
                <div class="control has-icons-right">
                    <input name="name" class="input" type="text" placeholder="" value="{{ $data->name??''}}">
                </div>
            </div>

            <div class="field" data-validation="['NE', 'EMAIL']" data-alias="Email">
                <div class="control has-icons-right">
                    <input name="email" class="input" type="text" placeholder="" value="{{ $data->email??''}}">
                </div>
            </div>

            <div class="field" data-validation="['L>=8']" data-alias="Contraseña">
                <div class="control has-icons-right">
                    <input name="password" class="input" type="password" placeholder="" value="{{ $data->password?? '' }}">
                </div>
            </div>

        @can("crear-roles")
            <modal
                    :id="'modalForm'"
                    :prop-form-reset="true"
                    :prop-title="'Nuevo Rol'"
                    :prop-form="{
                        name:'',
                        data:{}
                    }">
            </modal>
        @endcan

        @can('asignar-roles')
            <select-multiple
                    :rules="`['OPT']`"
                    :title="'Roles'"
                    :name="'roles'"
                    :url="{
                        fetch:'roles',
                        save:'roles/nuevo/save'
                    }"
                    :value="{{isset($data->roles)?$data->roles->pluck('id'):'[]'}}">
            </select-multiple>
        @endcan

            {{-- <div class="field" data-validation="[]" data-alias="Unselect">
                <div class="control has-icons-left">
                    <div class="select">
                        <select name="unselect">
                            <option value=""></option>
                            {!! hArraysTest( (isset($data->name) ? $data->name : null), true ) !!}
                        </select>
                    </div>
                </div>
            </div> --}}

        </div>

        <div class="column">
            <div class="wo2-fileupload" data-group="galeria" data-video="1" data-limit="0"></div>
        </div>

    </div>
@endsection
@prepend('scripts')
   <script src="{{ w2js('vue-app.js') }}"></script>
@endprepend
