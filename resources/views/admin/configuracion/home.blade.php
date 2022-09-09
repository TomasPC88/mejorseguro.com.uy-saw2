@extends('admin.layout.new')
@section('form_content')
    <input type="hidden" name="id" value="{{ $data->id??'' }}" />
    <div class="columns is-centered">
 
        <div class="column is-half">

            <div class="columns">
                <div class="column">
                    <div class="columns">
                        <div class="column">
                            <div class="field" data-validation="['NE']" data-alias="Nombre del Sitio" data-icon="">
                                <div class="control has-icons-right">
                                    <input name="app_name" class="input" type="text" placeholder="" value="{{ $data->app_name??'' }}">
                                </div>
                            </div>
                            
                            <div class="field" data-validation="['NE','EMAIL']" data-alias="E-mail de respuesta" data-icon="">
                                <div class="control has-icons-right">
                                    <input name="email_from_address" class="input" type="text" placeholder="" value="{{ $data->email_from_address??'' }}">
                                </div>
                            </div> 
                            
                            <div class="field" data-validation="['NE','EMAIL']" data-alias="E-mail de contacto" data-icon="">
                                <div class="control has-icons-right">
                                    <input name="email_to_contact" class="input" type="text" placeholder="" value="{{ $data->email_to_contact??'' }}">
                                </div>
                            </div>   
                        </div>                
                    </div>
                </div>
    
                <div class="column">
                    <div class="columns">
                        <div class="column">
                            <div class="field" data-validation="['OPT']" data-alias="Teléfono" data-icon="">
                                <div class="control has-icons-right">
                                    <input name="phone" class="input" type="text" placeholder="" value="{{ $data->phone??'' }}">
                                </div>
                            </div>
                            
                            <div class="field" data-validation="['OPT']" data-alias="Móvil" data-icon="">
                                <div class="control has-icons-right">
                                    <input name="mobile" class="input" type="text" placeholder="" value="{{ $data->mobile??'' }}">
                                </div>
                            </div> 
                        </div>                
                    </div>
                </div>
            </div>
            <hr>
            <h1 class="title">G-Recaptcha</h1>
            <div class="columns">
                <div class="column">
                    <div class="field" data-validation="['NE']" data-alias="Llave Pública" data-icon="">
                        <div class="control has-icons-right">
                            <input name="recaptcha_public" class="input" type="text" placeholder="" value="{{ $data->recaptcha_public??'' }}">
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="field" data-validation="['NE']" data-alias="Llave Privada" data-icon="">
                        <div class="control has-icons-right">
                            <input name="recaptcha_secret" class="input" type="text" placeholder="" value="{{ $data->recaptcha_secret??'' }}">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <h1 class="title">JS Snippets</h1>
            <div class="columns">
                <div class="column">
                    <div class="field" data-validation="['OPT']" data-alias="Cabecera" data-icon="">
                        <div class="control has-icons-right">
                            <textarea name="h_script" class="input" placeholder="">{{ $data->h_script??'' }}</textarea>
                        </div>
                    </div>
                    <div class="field" data-validation="['OPT']" data-alias="Pie de Página" data-icon="">
                        <div class="control has-icons-right">
                            <textarea name="f_script" class="input" placeholder="">{{ $data->f_script??'' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       {{--  <div class="column">
            <div class="wo2-fileupload" data-video="0" data-type="image" data-limit="1"></div>
        </div> --}}
    </div>
@endsection