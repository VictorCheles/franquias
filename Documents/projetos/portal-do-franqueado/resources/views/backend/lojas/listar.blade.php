@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $box = 'box-black' ?>
@else
    <?php $layout = 'layouts.app'; $box = 'box-danger' ?>
@endif

@extends($layout)

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Lojas
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
            <div class="box {{ $box }} box-solid">
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
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Loja</th>
                                <th>CEP</th>
                                <th>UF</th>
                                <th>Cidade</th>
                                <th>Bairro</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($lista->count() > 0)
                                @foreach($lista as $item)
                                    <tr>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->cep }}</td>
                                        <td>{{ $item->uf }}</td>
                                        <td>{{ $item->cidade }}</td>
                                        <td>{{ $item->bairro }}</td>
                                        <td class="options">
                                            <div class="row">
                                            @if(str_is('*' . env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', url()->current()) and $user->hasRoles([\App\ACL\Recurso::ADM_LOJAS_EDITAR]))

                                                @if($item->fazer_pedido)
                                                <div class="col-md-4"> 
                                                    <a href="{{ url('/backend/franquias/editar/' . $item->id . '/fazerpedido') }}"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Bloquear pedido" class="fa fa-ban"></i></a>
                                                </div>
                                                @else
                                                <div class="col-md-4"> 
                                                    <a href="{{ url('/backend/franquias/editar/' . $item->id . '/fazerpedido') }}"  class="btn btn-success fake-submit" style="width: 100%"><i title="Liberar pedido" class="fa fa-check"></i></a>
                                                </div>
                                                @endif
                                                <div class="col-md-3"> 
                                                    <a href="{{ url('/backend/franquias/editar', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    @if(Request::get('filter'))
                                        <td colspan="9">Nenhuma franquia encontrada</td>
                                    @else
                                        <td colspan="9">Nenhuma franquia cadastrada</td>
                                    @endif
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('backend.lojas.modals.filtro')
@endsection