@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Resultados da enquete: {{ $item->nome }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @foreach($item->perguntas as $pergunta)
                <div class="box box-black box-solid">
                    <div class="box-header">
                        <h3 class="box-title">{{ $pergunta->pergunta }}</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="form-group">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Pergunta</th>
                                        <th>Total de votos</th>
                                        <th>Porcentagem de votos</th>
                                    </tr>
                                </thead>
                                <tboby>
                                    @foreach($pergunta->respostas as $resposta)
                                        <tr>
                                            <td>{{ $resposta->resposta }}</td>
                                            <td>{{ $resposta->total_de_votos }}</td>
                                            <td width="25%">{{ $resposta->porcentagem_de_votos }}%</td>
                                        </tr>
                                    @endforeach
                                </tboby>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Respostas individuais</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <div class="form-group">
                        <table class="table table-striped table-bordered ">
                            <thead>
                                <tr>
                                    <td>Pergunta</td>
                                    <td>Resposta</td>
                                </tr>
                            </thead>
                            @foreach($item->perguntas as $pergunta)
                                <tr>
                                    <td>{{ $pergunta->pergunta }}</td>
                                    <td>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Usuário</th>
                                                    <th>Resposta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pergunta->respostasUsuarios as $resp)
                                                    <tr>
                                                        <td>{{ $resp->user->nome }}</td>
                                                        <td>{{ $resp->resposta->resposta }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
                <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Quem deve responder</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <div class="form-group">
                        <table class="table table-striped table-bordered ">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Respondeu?</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->destinatarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->nome }}</td>
                                        <td>{!! $usuario->enqueteRespostas($item->id)->count() > 0 ? '<span class="label label-success">Sim</span>' : '<span class="label label-danger">Não</span>' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection