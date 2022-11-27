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
        .only-print
        {
            display: none;
        }
        @media print
        {
            .only-print
            {
                display: block;
            }
        }
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
                                <td align="center" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 24px; color: #353535; padding:3%; padding-top:40px; padding-bottom:40px;">
                                    {{ $item->formulario->descricao }}
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                Unidade
                                            </th>
                                            <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                {{ $item->loja->nome }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                Data
                                            </th>
                                            <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="60%" align="left" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #000; padding:10px; padding-right:0;">
                                                Consultor
                                            </th>
                                            <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                {{ $item->user->nome }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <hr width="94%">
                                </td>
                            </tr>
                            @php
                            $fotos = [];
                            $acoes_corretivas = [];
                            @endphp
                            @foreach($item->formulario->topicos as $i => $topico)
                                <tr>
                                    <td align="center">
                                        <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                            <tr bgcolor="#000">
                                                <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                    <b>{{ $topico->descricao }}</b>
                                                    <hr class="only-print">
                                                </td>
                                                <td width="80%" align="right" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-left:0;">
                                                    {{--@php--}}
                                                        {{--$total = $topico->perguntas->count();--}}
                                                        {{--$pontuacao = $item->pontuacaoPorTopico($topico->id);--}}
                                                        {{--$porcentagem = (float) $item->porcentagemPorTopico($topico->id);--}}
                                                        {{--$porcentagem = number_format(str_replace(',', '.', $porcentagem), 2);--}}
                                                    {{--@endphp--}}
                                                    {{--Score: {{ $porcentagem }}%--}}
                                                    <hr class="only-print">
                                                </td>
                                            </tr>
                                            @foreach($topico->perguntas as $j => $pergunta)
                                                {{--@php--}}
                                                    {{--$resposta = $item->respostaPergunta($pergunta);--}}
                                                    {{--foreach($resposta->fotos as $foto)--}}
                                                    {{--{--}}
                                                        {{--$fotos[] = asset('uploads/visitas/' . $foto);--}}
                                                    {{--}--}}
                                                {{--@endphp--}}
                                                <tr>
                                                    <td width="70%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                        {{ $pergunta->pergunta }}
                                                    </td>
                                                    <td width="30%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        @if($request['resposta'][$pergunta->id] == 'null')
                                                            <span class="label label-info">Não Avaliado</span>
                                                        @elseif($request['resposta'][$pergunta->id] == 1)
                                                            <span class="label label-success">Sim</span>
                                                        @else
                                                            @php
                                                                $acoes_corretivas[] = $request['acao_corretiva'][$pergunta->id];
                                                            @endphp
                                                            <span class="label label-danger">Não</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($acoes_corretivas) > 0)
                                <tr>
                                    <td align="center">
                                        <hr width="94%">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                            <tr bgcolor="#000">
                                                <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                    <b>Ação Corretiva</b>
                                                    <hr class="only-print">
                                                </td>
                                                <td width="80%" align="right" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-left:0;">
                                                    Data de correção
                                                    <hr class="only-print">
                                                </td>
                                            </tr>
                                            @foreach($acoes_corretivas as $acao_corretiva)
                                                <tr>
                                                    <td width="70%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                        {{ $acao_corretiva['descricao'] }}
                                                    </td>
                                                    <td width="30%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        {{ \Carbon\Carbon::parse($acao_corretiva['data_correcao'])->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            @if(isset($request['notificacao']) and count($request['notificacao']) > 0)
                                <tr>
                                    <td align="center">
                                        <hr width="94%">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                            <tr bgcolor="#000">
                                                <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                    <b>Notificações</b>
                                                    <hr class="only-print">
                                                </td>
                                                <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                    <b>Valor unitário</b>
                                                    <hr class="only-print">
                                                </td>
                                                <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                    <b>Quantidade</b>
                                                    <hr class="only-print">
                                                </td>
                                                <td width="40%" align="right" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-left:0;">
                                                    Subtotal
                                                    <hr class="only-print">
                                                </td>
                                            </tr>
                                            @php
                                                $total = 0;
                                            @endphp
                                            @foreach($request['notificacao'] as $n_id => $notificacao)
                                                @php
                                                    $n = \App\Models\ConsultoriaCampo\Notificacao::findOrFail($n_id);
                                                    $total += ($notificacao['quantidade'] * $n->valor_un)
                                                @endphp
                                                <tr>
                                                    <td width="20%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                        {{ $n->descricao }}
                                                    </td>
                                                    <td width="20%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                        {{ $n->valor_formatted }}
                                                    </td>
                                                    <td width="20%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                        {{ $notificacao['quantidade'] }}
                                                    </td>
                                                    <td width="40%" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        R${{ number_format($notificacao['quantidade'] * $n->valor_un, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="right" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-left:0;">
                                                        <b>Total:</b> R${{ number_format($total, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td align="center">
                                    <hr width="94%">
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                        <tr bgcolor="#000">
                                            <td width="20%" align="left" bgcolor="#000" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #EEEEEE; padding:10px; padding-right:0;">
                                                <b>Relato final</b>
                                                <hr class="only-print">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="100%" align="left" bgcolor="#FFFFFF" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 12px; color: #252525; padding:10px; padding-right:0;">
                                                {{ $request['relato_final'] }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="94%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td colspan="5" align="left" valign="middle" style="font-family: Verdana, Geneva, Helvetica, Arial, sans-serif; font-size: 14px; color: #353535; padding:3%; padding-top:40px; padding-bottom:40px;">
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

