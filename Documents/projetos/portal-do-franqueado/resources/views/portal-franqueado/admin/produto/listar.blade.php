@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Produtos
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço unitário</th>
                            <th>Categoria</th>
                            <th>Disponível?</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <?php $categoria = $item->categoria; ?>
                                <tr>
                                    <td>
                                        @if($item->img)
                                            <a class="colorbox" href="{{ $item->img }}">
                                                <img height="50" width="50" src="{{ $item->img }}">
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ str_limit($item->descricao, 50) }}</td>
                                    <td>{!! $item->preco_formatted !!}</td>
                                    <td>{{ $categoria ? $categoria->nome : '' }}
                                    <td>{!! $item->disponivel_categoria_formatted !!}</td>
                                    <td class="options">
                                        <div class="btn-group-vertical">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PRODUTOS_EDITAR]))
                                                <div class="col-md-2"> 
                                                    <a href="{{ route('admin.produto.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="6">Nenhum produto encontrada</td>
                                @else
                                    <td colspan="6">Nenhum produto cadastrada</td>
                                @endif
                            </tr>
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
    @include('portal-franqueado.admin.produto.modals.filtro')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '50%'
            });
        });
    </script>
@endsection