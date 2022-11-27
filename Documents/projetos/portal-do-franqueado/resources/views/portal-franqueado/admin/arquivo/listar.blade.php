@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .circle {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            background-color: #7d1a1d;
        }
        .btn:focus{
            background-color: #3c8dbc;
            color: #fff;
        }   
    </style>
@endsection

@section('content')

    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Arquivos - {{ \App\Models\Pasta::$setores[$id] }}
                </h1>
            </div>
        </section>
    </div>
    <div class="box">
        <div class="box-body no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">Pastas</th>
                        <th>Quantidade de Itens</th>
                    </tr>
                </thead>
                @forelse($lista as $item)
                    <tr>
                        <td style="width: 5%">
                            <div class="circle">
                                <img src="{{ asset('/images/folder-white-shape.png') }}" style="height: 25px; width: 25px; margin-left: 19%; margin-top: 15%">
                            </div>
                        </td>
                        <td>
                            <p style="margin-top: 2%">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $item->id }}" style="color: #000">{{ $item->nome }}</a>
                            </p>
                        </td>
                        <td>{{ $item->arquivos->count() }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="padding: 0;">
                            <div id="collapse{{ $item->id }}" class="panel-collapse collapse">
                                <div class="box-body">
                                    <table class="table table-bordered table-striped">
                                        @forelse($item->arquivos as $arquivo)
                                            <tr>
                                                <td style="text-transform: uppercase;">{{ $arquivo->extensao }}</td>
                                                <td>{{ $arquivo->nome }}</td>
                                                <td>{!! $arquivo->descricao !!}</td>
                                                <td>
                                                    <a class="btn btn-primary" href="{{ route('arquivo.download', $arquivo->id) }}">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <p>Nenhum arquivo cadastrado</p>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>Nenhuma pasta cadastrada</p>
                @endforelse
            </table>
        </div>
    </div>
    <div class="center pagination-black">
        {{ $lista->appends(Request::all())->links() }}
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.box-title').click(function () {
                $('.box-title a i').removeClass('fa-folder-open');
                $(this).children('a').children('i').addClass('fa-folder-open');
            })
        });
    </script>
 
@endsection