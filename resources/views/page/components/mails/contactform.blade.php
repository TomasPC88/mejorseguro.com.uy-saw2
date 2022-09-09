{{-- @php
    $data = [
        'nombre' => 'lolo',
        'email' => 'lolo@lolo.lo',
        'telefono' => '3521466',
        'consulta' => 'kjbfsjb dv bdf vdib ',
    ];
@endphp --}}
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
                                                            <td align="center"><img
                                                                        src="{{pw2img('mail-notifications/new_user_crop.png')}}"
                                                                        width="75" height="atuo"
                                                                        alt="picture"
                                                                        style="border-radius: 50%;background-color: #717070;padding: 0px 10px;"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="22"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center"
                                                                style="font:300 27px 'Open Sans', Arial, Helvetica, sans-serif; color:#252525;"
                                                                class="smallfont">En estos momentos un
                                                                usuario se ha puesto en contacto con nuestra empresa
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center"
                                                                style="font:700 27px 'Open Sans', Arial, Helvetica, sans-serif; color:#16c4a9;"
                                                                class="smallfont">y espera por nuestra atención.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="33">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td bgcolor="#ffffff">
                                                                <table align="left" width="43%" border="0"
                                                                       cellspacing="0" cellpadding="0" class="inner"
                                                                       style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td><img class="imgresponsive-bike"
                                                                                 src="{{pw2img('mail-notifications/subscription_croped.png')}}"
                                                                                 width="261"
                                                                                 height="atuo" alt="picture"/></td>
                                                                    </tr>
                                                                </table>
                                                                <table align="left" width="57%" border="0"
                                                                       cellspacing="0" cellpadding="0" class="inner"
                                                                       style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td height="22">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <table width="100%" border="0"
                                                                                   cellspacing="0" cellpadding="0"
                                                                                   class="inner">
                                                                                <tr>
                                                                                    <td width="39" class="sidespace">
                                                                                        &nbsp;
                                                                                    </td>
                                                                                    <td>
                                                                                        <table width="100%" border="0"
                                                                                               cellspacing="0"
                                                                                               cellpadding="0">
                                                                                            <tr>
                                                                                                <td style="font:bold 20px Arial, Helvetica, sans-serif; color:#333332;">
                                                                                                    Información de
                                                                                                    contacto
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="5"></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="font:14px/19px Arial, Helvetica, sans-serif; color:#333332;">
                                                                                                    <ul>
                                                                                                        <li>
                                                                                                            <b>Nombre: </b>{{ $data['nombre'] }}
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <b>Email: </b>{{ $data['email'] }}
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <b>País: </b>{{ $data['pais'] }}
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <b>Teléfono: </b>{{ $data['telefono'] }}
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <b>Consulta: </b>{{ $data['consulta'] }}
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="20">&nbsp;
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <table width="100%"
                                                                                                           border="0"
                                                                                                           cellspacing="0"
                                                                                                           cellpadding="0">
                                                                                                        <tr>
                                                                                                            <td width="50%"
                                                                                                                align="left">
                                                                                                                <a href="mailto:{{ $data['email'] }}"
                                                                                                                   style="font:bold 12px/29px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none; background:#16c4a9; float:left; padding:0 19px; border-radius:24px; text-transform:uppercase;">
                                                                                                                    Escribale
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                    <td width="25" class="sidespace">
                                                                                        &nbsp;
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td height="22">&nbsp;</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
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