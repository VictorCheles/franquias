<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type="text/css">
        #outlook a {padding:0;}
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;} /* force default font sizes */
        .ExternalClass {width:100%;} .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Hotmail */
        table td {border-collapse: collapse;}
        @media only screen and (min-width: 600px) { .maxW { width:600px !important; } }
    </style>
</head>
<body style="margin: 0px; padding: 0px; -webkit-text-size-adjust:none; -ms-text-size-adjust:none;" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF">
<table bgcolor="#CCCCCC" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top">
            <table width="100%" class="maxW" style="max-width: 600px; margin: auto;" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" align="center">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <img src="{{ asset('images/logo.png') }}" width="300">
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 24px; color: #353535; padding:3%; padding-top:40px; padding-bottom:10px;">
                                    Resposta - Cliente Oculto
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #353535; padding:3%; padding-top:10px; padding-bottom:10px;">
                                    <b>Cliente</b>: {{ $user->nome }}
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #353535; padding:3%; padding-top:10px; padding-bottom:10px;">
                                    <b>Formulario</b>: {{ $form->nome }}
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #353535; padding:3%; padding-top:10px; padding-bottom:40px;">
                                    <b>Loja</b>: {{ $loja->nome }}
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td width="60%" align="left" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                Pergunta
                                            </td>
                                            <td width="40%" align="center" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                Resposta
                                            </td>
                                        </tr>
                                        @foreach($form->perguntas as $pergunta)
                                            <?php $resposta = $pergunta->resposta($loja->id, $user->id)->first(); ?>
                                            <tr>
                                                <td width="60%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                    {{ $pergunta->pergunta }}
                                                </td>
                                                <td width="40%" align="center" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                    {!! $resposta->resposta_formatted !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 14px; color: #353535; padding:3%; padding-top:40px; padding-bottom:40px;">
                                                Portal do Franqueado | {{ env('APP_NAME') }} {{ date('Y') }}
                                            </td>
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
</body>
</html>

