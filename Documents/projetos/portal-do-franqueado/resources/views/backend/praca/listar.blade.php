@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $box = 'box-black'; ?>
@else
    <?php $layout = 'layouts.app'; $box = 'box-danger';?>
@endif

@extends($layout)

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Praças
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
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Data limite pedido</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->data_limite_pedido)->format('d/m/Y \a\s H:i') }}</td>
                                    <td class="options">
                                        <div class="row">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PRACAS_EDITAR]))
                                                <div class="col-md-4"> 
                                                    <a href="{{ route('backend.praca.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="2">Nenhuma Praça encontrada</td>
                                @else
                                    <td colspan="2">Nenhuma Praça cadastrada</td>
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
@endsection