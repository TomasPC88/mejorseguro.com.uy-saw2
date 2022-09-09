<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

{{--    <link rel="stylesheet" href="{{ pw2css('sad-mails.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ pw2fonts('Roboto/stylesheet.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('page_assets/fonts/library/stylesheet.css') }}">--}}
    <link href="https://fonts.googleapis.com/css?family=Londrina+Outline&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Londrina+Outline&display=swap');
    </style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
    <tr>
        <td height="54">&nbsp;</td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
    <tr>
        <td align="center"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
                <tr>
                    <td><table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center" class="full" style="border-radius:5px 5px 0 0; background-color:#ffffff;">
                            <tr>
                                <td height="29">&nbsp;</td>
                            </tr>
                            <tr>
                                <td><table border="0" cellspacing="0" cellpadding="0" align="left" class="inner" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td width="23" class="hide">&nbsp;</td>
                                            <td height="75" class="inner" valign="middle">
                                                {{ $header ?? '' }}
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="150" border="0" cellspacing="0" cellpadding="0" align="right" class="inner" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                        <tr>
                                            <td height="15">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="center"><a href="{{ config('app.url') }}" class="top-button" style="font:bold 13px/37px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none; background:#16c4a9; display:block; border-radius:24px; text-transform:uppercase;">Ver Online</a></td>
                                            <td class="hide" width="20">&nbsp;</td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td style="border-bottom:1px solid #dbdbdb;">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
    </tr>
</table>
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{--{{ $subcopy ?? '' }}--}}
{{ $footer ?? '' }}
</body>

</html>