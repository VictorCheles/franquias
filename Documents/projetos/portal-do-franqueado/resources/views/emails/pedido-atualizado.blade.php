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
                                        <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 24px; color: #353535; padding:3%; padding-top:40px; padding-bottom:0px;">
                                            Pedido #{{ $item->id }} | Loja {{ $item->loja->nome }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 24px; color: #353535; padding:3%; padding-top:0px; padding-bottom:40px;">
                                            Atualizado às {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td width="20%" align="left" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                        Item
                                                    </td>
                                                    <td width="20%" align="left" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                        Valor unitário
                                                    </td>
                                                    <td width="20%" align="left" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                        Quantidade
                                                    </td>
                                                    <td width="20%" align="left" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                        Peso
                                                    </td>
                                                    <td width="20%" align="right" bgcolor="#252525" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-left:0;">
                                                        Subtotal
                                                    </td>
                                                </tr>
                                                @foreach($item->produtos as $produto)
                                                    <tr>
                                                        <td width="20%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                            {{ $produto->nome }}
                                                        </td>
                                                        <td width="20%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                            {{ maskMoney($produto['pivot']->preco) }}
                                                        </td>
                                                        <td width="20%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                            {{ $produto['pivot']->quantidade }}
                                                        </td>
                                                        <td width="20%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                            {{ $produto['pivot']->quantidade * $produto->peso }}kg
                                                        </td>
                                                        <td width="20%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                            {{ maskMoney($produto['pivot']->quantidade * $produto['pivot']->preco) }}
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
                                                    <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                        Solicitado em
                                                    </th>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                        Data prevista de entrega
                                                    </th>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não definida' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                        Multa por atraso
                                                    </th>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        {{ maskMoney($item->multa) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                        Peso total
                                                    </th>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        {{ $item->pesoTotal() }}kg
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                        Valor total
                                                    </th>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        <b>{{ maskMoney($item->valorTotal() + $item->multa) }}</b>
                                                    </td>
                                                </tr>
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

