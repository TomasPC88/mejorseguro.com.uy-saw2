@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img class="logo" src="{{ pw2img("logo.png") }}" width="180" height="61" alt="Logo">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
                <tr>
                    <td align="center">
                        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center"
                               class="devicewidth">
                            <tr>
                                <td>
                                    <table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0"
                                           align="center" class="full" style="border-radius:0 0 7px 7px;">
                                        <tr>
                                            <td height="18">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table class="inner" align="right" width="340" border="0"
                                                       cellspacing="0" cellpadding="0"
                                                       style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align:center;">
                                                    <tr>
                                                        <td width="21">&nbsp;</td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0"
                                                                   cellpadding="0" align="center">
                                                                <tr>
                                                                    <td>
                                                                        <table width="100%" border="0" cellspacing="0"
                                                                               cellpadding="0" align="center"
                                                                               class="full">
                                                                            <tr>
                                                                                <td align="center"
                                                                                    style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;">
                                                                                    <p style="font-size: 12px; font-family:Helvetica,  Arial, sans-serif; color:#676666;">Powered by <a
                                                                                                href="https://sitios.com.uy"
                                                                                                style="font-size: 12px; font-family:Roboto; color:#676666;">Sitios Agencia Digital</a>
                                                                                    </p>
                                                                                </td>
                                                                                <!-- <td style="color:#000000;"> |</td>
                                                                                <td align="center"
                                                                                    style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;">
                                                                                    <a style="color:#000000; text-decoration:none;"
                                                                                       href="#"> Unsubscribe</a></td> -->
                                                                                <td style="color:#000000;"> |</td>
                                                                                <td align="center"
                                                                                    style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;">
                                                                                    <ul style="list-style: none; padding:0px; margin: 0px">
                                                                                        <li style=" display:inline-block; margin: 0px 5px;"><a
                                                                                                    href="https://www.facebook.com/sitiosad/"><img alt=""
                                                                                                                                                   src="https://sitios.com.uy/mailing/uploads/1529346512.png"
                                                                                                                                                   style="height: 30px; width:auto;"/>
                                                                                            </a></li>
                                                                                        <li style=" display:inline-block; margin: 0px 5px;"><a
                                                                                                    href="https://www.instagram.com/sitiosagenciadigital/"><img alt=""
                                                                                                                                                                src="https://sitios.com.uy/mailing/uploads/1529346458.png"
                                                                                                                                                                style="height: 30px; width:auto;"/>
                                                                                            </a></li>
                                                                                    </ul>
                                                                                </td>
                                                                                <td class="hide" width="40"
                                                                                    align="right">&nbsp;
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="18">&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table class="inner" align="left" width="230" border="0" cellspacing="0"
                                                       cellpadding="0"
                                                       style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align:center;">
                                                    <tr>
                                                        <td width="21">&nbsp;</td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0"
                                                                   cellpadding="0" align="center">
                                                                <tr>
                                                                    <td align="center"
                                                                        style="font:11px Helvetica,  Arial, sans-serif; color:#000000;">
                                                                        &copy; {{ Date('Y') }} Todos los derechos reservados
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="18">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td width="21">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
    @push('scripts')
        <script src="{{ pw2js('plugins/jssocials.min.js') }}"></script>
        <script>
            $("#social-box").jsSocials({
                showLabel: false, showCount: false, shares: ["facebook", "twitter", "linkedin", "pinterest"]
            });
        </script>
    @endpush
@endcomponent