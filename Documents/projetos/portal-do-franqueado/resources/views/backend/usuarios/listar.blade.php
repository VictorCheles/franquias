@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $box = 'box-black'; ?>
@else
    <?php $layout = 'layouts.app'; $box = 'box-danger'; ?>
@endif

@extends($layout)

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Usuários
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
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Nível de acesso</th>
                                <th>Grupo de permissões</th>
                                <th>Status</th>
                                <th>Loja</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <?php $loja = $item->loja; ?>
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{!! $item->nivel_acesso_formatted !!}</td>
                                    <td>{!! $item->grupoACL ? $item->grupoACL->nome : '<a href="' . url('backend/usuarios/editar', $item->id) . '" class="label label-danger">USUÁRIO SEM GRUPO</a>' !!}</td>
                                    <td>{!! $item->status_formatted !!}</td>
                                    <td>{{ $loja ? $loja->nome : '' }}</td>
                                    <td class="options">
                                        <div class="row">
                                            @if(str_is('*' . env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', url()->current()) and $user->hasRoles([\App\ACL\Recurso::ADM_USUARIOS_EDITAR]))
                                                <div class="col-md-4"> 
                                                    <a href="{{ url('/backend/usuarios/editar', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if(!Session::has('personificacao'))
                                                @if(
                                                    str_is('*' . env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', url()->current())
                                                    and $user->hasRoles([\App\ACL\Recurso::ADM_PERSONIFICACAO])
                                                    and $item->id != Auth::user()->id
                                                    and $item->status == \App\User::STATUS_ATIVO
                                                )
                                                    <div class="col-md-5"> 
                                                        <a href="{{ url('/backend/usuarios/personificar', $item->id) }}" class="btn btn-info"><i title="Personificar" class="fa fa-user"></i></a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="9">Nenhum usuário encontrado</td>
                                @else
                                    <td colspan="9">Nenhum usuário cadastrado</td>
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
    @include('backend.usuarios.modals.filtro')
@endsection