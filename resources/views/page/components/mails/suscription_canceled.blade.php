@php($date = new DateTime())
@component('mail::message')
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
                    <tr>
                        <td>
                            <table width="100%" bgcolor="#f1f1f1" border="0" cellspacing="0" cellpadding="0"
                                   align="center" class="full">
                                <tr>
                                    <td height="35">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
                                            <tr>
                                                <td width="23" class="sidespace">&nbsp;</td>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                                           align="center">
                                                        <tr>
                                                            <td align="center"><img src="{{pw2img('mail-notifications/sub_canceled.png')}}"
                                                                                    width="250px" height="atuo"
                                                                                    alt="picture"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td height="22"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="font:300 27px 'Open Sans', Arial, Helvetica, sans-serif; color:#252525;" class="smallfont">
                                                                <hr>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" style="font:700 18px 'Open Sans', Arial, Helvetica, sans-serif; color:#16c4a9;" class="smallfont">
                                                                <p style="text-align: center; font-family: 'Roboto';">{{__('messages.sus_cancel_texto')}} <a
                                                                            href="{{route('page.home')}}" style="text-decoration: none">{{cache('config')->app_name}}</a></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="33">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td height="32"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="23" class="sidespace">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endcomponent