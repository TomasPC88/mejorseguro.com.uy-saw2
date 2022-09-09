@extends('admin.layout.new')
@section('form_content')
<input type="hidden" name="id" value="{{ $data->id ?? '' }}"/>
<div class="columns">

    <div class="column">

        <div class="field" data-validation="[]" data-alias="Email">
            <div class="control has-icons-right">
                <input name="email" class="input" disabled type="text" placeholder=""
                       value="{{ $data->email ?? '' }}">
            </div>
        </div>

        <div class="column">
            <div class="field" data-validation="['OPT']" data-alias="Activo">
                <input type="checkbox" name="active[]"
                       class="switch" {{ isset($data->active) ? ($data->active ? 'checked' : '') : '' }} />
            </div>
        </div>

    </div>

    <div class="column">
    </div>
</div>
@endsection