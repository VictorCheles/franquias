@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Lista de Usuários
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
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding" style="overflow: visible;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Data de cadastro</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <?php
                                $bg = '';
                                if ($item->respondeuAlgo()) {
                                    $bg = 'style="background: #aef6ae"';
                                }
                                ?>
                                <tr {!! $bg !!}>
                                    <td>
                                        {{ $item->nome }}
                                        {!! $item->respondeuAlgo() ? '<i class="fa fa-check" style="color: green"></i>' : '' !!}
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->telefone }}</td>
                                    <td>{{ $item->cidade }}</td>
                                    <td>{{ $item->uf }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="options">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default" data-toggle="dropdown">Mais opções</button>
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_USUARIOS_EDITAR]))
                                                    <li>
                                                        <a href="{{ route('avaliadoroculto.users.edit', $item->id) }}">
                                                            <i class="fa fa-edit"></i> Editar informações do Avaliador
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" rel="modal" data-modal="{{ $item->id }}">
                                                            <i class="fa fa-info"></i> Perfil do Avaliador
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('avaliadoroculto.users.formularios', $item->id) }}">
                                                            <i class="fa fa-check-square"></i> Questionários respondidos
                                                        </a>
                                                    </li>
                                                    @if($item->formularios->count() > 0)
                                                        <li>
                                                            <a href="{{ route('avaliadoroculto.users.email.chamada', $item->id) }}">
                                                                <i class="fa fa-envelope-o"></i> Enviar convite para avaliação
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <form class="swal-confirmation" action="{{ route('avaliadoroculto.users.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <a href="#" class="fake-submit btn" style="width: 100%; text-align: left;"><i class="fa fa-trash"></i> Deletar</a>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="7">Nenhum usuário encontrado</td>
                                @else
                                    <td colspan="7">Nenhum usuário cadastrado</td>
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
    @include('avaliador-oculto.admin.users.modal-detalhes')
    @include('avaliador-oculto.admin.users.modal-filtro')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent('form');
                swal({
                    title: "Deletar avaliador oculto?",
                    text: "Esta operação não pode ser desfeita e removerá todas as respostas feitas por esse avaliador, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar avaliador oculto",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        form.submit();
                    } else {
                        return false;
                    }
                });
            return false;
            });
        });
    </script>
@endsection