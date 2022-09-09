@component('mail::message')
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
                    <tr>
                        <td>
                            <table width="100%" bgcolor="#313131" border="0" cellspacing="0" cellpadding="0"
                                   align="center" class="full"
                                   style=" background-image:url('{{ pw2img('mail-notifications/white-bg.gif') }}'); background-repeat:repeat-x; background-position:left top;">
                                <tr>
                                    <td height="23">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center"><img src="{{ pw2img('mail-notifications/user_reg.png') }}" width="83" height="atuo"
                                                            alt="picture"/></td>
                                </tr>
                                <tr>
                                    <td height="23">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center"
                                        style="font:300 27px 'Open Sans', Arial, Helvetica, sans-serif; color:#16c4a9;"
                                        class="smallfont">{{__('messages.sus_nueva_texto')[0]}} {{ cache('config')->app_name }} <br>
                                        {{__('messages.sus_nueva_texto')[1]}}
                                    </td>
                                </tr>
                                <tr>
                                    <td height="16">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tr>
                                                <td width="40%" height="2"></td>
                                                <td width="10%" height="2"
                                                    style=" border-bottom:2px solid #ffffff;"></td>
                                                <td width="40%" height="2"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="16">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <span style="font:700 37px 'Open Sans', Arial, Helvetica, sans-serif; color:#ffffff;"
                                              class="smallfont">{{__('messages.sus_nueva_texto')[2]}}</span>
                                        <br>
                                        <span style="font:700 25px 'Open Sans', Arial, Helvetica, sans-serif; color:#ffffff;"
                                              class="smallfont">{{__('messages.sus_nueva_texto')[3]}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="16">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td align="center" class="template-img">
                                                    <img src="{{ pw2img('banner/all-banner.jpg') }}" width="100%" height="atuo" alt="Banner"/>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="25">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                            <tr>
                                                <td width="23" class="sidespace">&nbsp;</td>
                                                <td align="center"
                                                    style="font:15px/19px Arial, Helvetica, sans-serif; color:#ffffff;">
                                                    {{__('messages.sus_nueva_texto')[4]}}
                                                </td>
                                                <td width="23" class="sidespace">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="25">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center" class="readmore-button">
                                        <a href="{{ route('page.unsuscribe', compact('token')) }}" style="font:bold 12px/29px Arial, Helvetica, sans-serif; color:#16c4a9; text-decoration:none; background:#ffffff; display:inline-block; padding:0 19px; border-radius:24px; text-transform:uppercase;">
                                            {{__('messages.sus_nueva_texto')[5]}}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="16"></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
{{--    <div style="min-height: 480px">--}}
{{--        <div style="position: relative; width: 75%; padding: 15px; border: solid 8px #fff; margin-left: auto; margin-right: auto; margin-top: 30px; min-height: 300px">--}}
{{--            <h5 style="width: calc(100% - 30px); position: absolute; top: 20px; text-align: center; margin: auto; font-family: 'Roboto'; font-size: 20px; font-weight: normal; font-style: normal; color: #c7c7c7;">--}}
{{--                Desde {{ cache('config')->app_name }} le decimos:</h5>--}}
{{--            <div style="position: absolute; width: 100%; top: 70px; left: 0px">--}}
{{--                <div style="position: absolute; top: 0px; left: -25px; height: 105px; width: 50px; background-color: #ececec;"></div>--}}
{{--                <h1 style="width: 100%; position: absolute; left: 0px; z-index: 1; background-color: #ececec; text-align: center; margin: auto; font-family: 'Roboto'; font-size: 4rem; font-weight: bold; text-transform: uppercase; font-style: normal; color: #c7c7c7;">--}}
{{--                    Gracias</h1>--}}
{{--                <div style="position: absolute; top: 0px; right: -25px; height: 105px; width: 50px; background-color: #ececec;"></div>--}}
{{--            </div>--}}
{{--            <h5 style="width: calc(100% - 30px); position: absolute; top: 180px; text-align: center; margin: auto; font-family: 'Roboto'; font-size: 20px; font-weight: normal; font-style: normal; color: #c7c7c7;">--}}
{{--                Por suscribirte a nuestro newssletter</h5>--}}
{{--        </div>--}}
{{--        <p style="text-align: center; font-family: 'Roboto'; color: #232323d6; margin-top: 1rem; font-size: 18px">--}}
{{--            Estimado usuario gracias por suscribirse en <a href="{{route('page.home')}}">{{env('APP_NAME')}}</a>, podrá--}}
{{--            mantanerse--}}
{{--            actualizado de todas nuestras novedades.</p>--}}
{{--        <hr style="margin: 20px 0px">--}}
{{--        <div style="width: 100%; text-align: center">--}}
{{--            <a style="text-decoration: none; font-weight: bold; background-color: #2b336b; padding: 11px 23px; border-radius: 5px; color: #fff; font-family: 'Roboto';"--}}
{{--               href="{{ route('page.unsuscribe', compact('token')) }}">No deseo--}}
{{--                recibir notificaciones</a>--}}
{{--        </div>--}}
{{--    </div>--}}
@endcomponent