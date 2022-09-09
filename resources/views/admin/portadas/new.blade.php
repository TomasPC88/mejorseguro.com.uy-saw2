@extends('admin.layout.new')
@section('form_content')
    <input type="hidden" name="id" value="{{ $data->id??'' }}" />
    <div class="columns">

        <div class="column">

            @component('admin.components.locale-tabs',['name'=>'portadas'])
                @foreach(explode(',',config('app.locales')) as $locale)
                    @php( $translation = isset($data->translations)?$data->translations->where('locale',$locale)->first():null)
                    @slot($locale)
                        <div class="field" data-validation="['OPT']" data-alias="Nombre ({{$locale}})"
                             data-icon="">
                            <div class="control has-icons-right">
                                <input name="translations[{{$locale}}][name]" class="input"
                                       type="text"
                                       placeholder=""
                                       value="{{ $translation->name??''}}">
                            </div>
                        </div>
                        <div class="field" data-validation="['OPT']" data-alias="Descripción ({{$locale}})" data-icon="">
                            <div class="control has-icons-right">
                                <input name="translations[{{$locale}}][description]" class="input" type="text" placeholder="" value="{{ $translation->description??''  }}">
                            </div>
                        </div>
                    @endslot
                @endforeach
            @endcomponent

            <div class="columns">
                <div class="column is-three-quarters">
                    <div class="field" data-validation="['OPT']" data-alias="URL" data-icon="">
                        <div class="control has-icons-right">
                            <input name="url" class="input" type="text" placeholder="" value="{{ $data->url??'' }}">
                        </div>
                    </div>
                </div>
                <div class="column is-pulled-right">
                    <div class="field" data-validation="['OPT']" data-alias="Abrir link en otra página">
                        <input type="checkbox" name="target[]" class="switch" {{ isset($data->target) ? ($data->target ? 'checked' : '') : '' }} />
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="field" data-validation="['OPT']" data-alias="Activo">
                        <input type="checkbox" name="active[]" class="switch" {{ isset($data->active) ? ($data->active ? 'checked' : '') : 'checked' }} />
                    </div>
                </div>
            </div>

        </div>

        <div class="column">
            <div class="wo2-fileupload" data-video="0" data-type="image" data-limit="1"></div>
        </div>


    </div>
@endsection