@extends('admin.layout.new')
@section('form_content')
    <input type="hidden" name="id" value="{{ $data->id ?? '' }}"/>
    <div class="columns is-mobile is-centered">

        <div class="column is-three-quarters  is-narrow">

            <div class="field" data-validation="['NE']" data-alias="Nombre" data-icon="">
                <div class="control has-icons-right">
                    <input name="name" class="input" type="text" placeholder="" value="{{ $data->name??'' }}">
                </div>
            </div>

            <select-two
                    :rules="`['OPT']`"
                    :title="'Permisos'"
                    :name="'permissions'"
                    :url="'permissions/admin'"
                    :tags="{{$data->permissions??'[]'}}">
            </select-two>

        </div>

    </div>
@endsection
@prepend('scripts')
    <script src="{{ w2js('vue-app.js') }}"></script>
@endprepend
