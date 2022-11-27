@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Enquetes
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->descricao }}</td>
                                    <td>{{ $item->inicio->diffForHumans() }}</td>
                                    <td>{{ $item->fim->diffForHumans() }}</td>
                                    <td>{!! $item->aberta_formatted !!}</td>
                                    <td class="options">
                                        @if($item->aberta)
                                            <a href="{{ route('enquetes.responder', $item->id) }}" class="btn btn-flat btn-app">
                                                <i class="fa fa-check-square-o"></i> Responder
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="4">Nenhuma enquete encontrada</td>
                                @else
                                    <td colspan="4">Nenhuma enquete cadastrada</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="text-center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    <hr>
    <div class="center pagination-black">
        {{ $lista->appends(Request::all())->links() }}
    </div>
@endsection