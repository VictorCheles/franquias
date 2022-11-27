@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Estabelecimentos
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                    <div class="box-tools pull-right">
                        @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_CRIAR]))
                            <a href="{{ route('clientes_loja_estabelecimento.create') }}" style="color:#fff;" type="button" class="btn btn-flat btn-box-tool"><i class="fa fa-plus"></i> Adicionar Estabelecimento</a>
                        @endif
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Estabelecimento</th>
                                <th>Quem cadastrou</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($lista->count() > 0)
                                @foreach($lista as $item)
                                    <tr>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->user->nome }}</td>
                                        <td class="options">
                                            @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_EDITAR]))
                                                <a href="{{ route('clientes_loja_estabelecimento.edit', $item->id) }}" class="btn btn-flat btn-app">
                                                    <i class="fa fa-edit"></i> Editar
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@endsection