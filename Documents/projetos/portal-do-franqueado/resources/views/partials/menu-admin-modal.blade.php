<?php
$user = Auth::user();
?>
<style>
    .btn-100
    {
        width: 100%;
    }
    .btn-preto
    {
        background-color: #000; color: #ffffff !important;
    }
    .btn-vermelho
    {
        background-color: #7d1a1d; color: #ffffff !important;
    }
    .box-primary.box-menu-admin
    {
        border-color: #000 !important;border-style: solid !important; border-width: 2.0px !important;
    }

    .box-menu-admin .btn
    {
        padding: 5px 0 5px 0;
    }

    .box-menu-admin .fa
    {
        font-size: 50px;
    }

    @media (min-width: 768px)
    {
        .box-menu-admin .row .col-md-6:first-child
        {
            padding-right: 5px;
        }

        .box-menu-admin .row .col-md-6:last-child
        {
            padding-left: 5px;
        }
    }

</style>
<div class="modal modal-menu-admin fade">
    <div class="modal-dialog" style="width: 95%; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Menu admin</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_VITRINES))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-picture-o"></i>
                                        <h4>Vitrine</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.vitrine.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.vitrine.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_SETORES))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-users"></i>
                                        <h4>Setores</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_SETORES_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.setor.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_SETORES_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.setor.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_GRUPOS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-object-group"></i>
                                        <h4>Grupos</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_GRUPOS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.grupos.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_GRUPOS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.grupos.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_USUARIOS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-user"></i>
                                        <h4>Usuários</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_USUARIOS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ url('backend/usuarios/listar') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_USUARIOS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ url('backend/usuarios/criar') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_PRACAS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-building"></i>
                                        <h4>Praças</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_PRACAS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('backend.praca.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_PRACAS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('backend.praca.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_LOJAS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-home"></i>
                                        <h4>Lojas</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_LOJAS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ url('backend/franquias/listar') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_LOJAS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ url('backend/franquias/criar') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_ENQUETES))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-question-circle-o"></i>
                                        <h4>Enquetes</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_ENQUETES_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.enquetes.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_ENQUETES_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.enquetes.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole([\App\ACL\Recurso::ADM_DOWNLOAD_MAILLING]))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-file-excel-o"></i>
                                        <h4>Mailling Cupons</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ url('backend/clientes/listar/excel') }}" class="btn btn-flat btn-vermelho btn-100 password-mailling">Baixar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h4>Avaliador Oculto</h4>
                        <hr>
                    </div>
                    @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_DASHBOARD]))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-object-group"></i>
                                        <h4>Dashboard</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('avaliadoroculto.dashboard') }}" class="btn btn-flat btn-vermelho btn-100">Ir para Dashboard</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_USUARIOS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-user"></i>
                                        <h4>Usuários</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_USUARIOS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('avaliadoroculto.users.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_USUARIOS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('avaliadoroculto.users.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($user->hasAnyRole(\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS))
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-building"></i>
                                        <h4>Formulários</h4>
                                    </div>
                                    <div class="row">
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('avaliadoroculto.formularios.index') }}" class="btn btn-flat btn-vermelho btn-100">Listar</a>
                                            </div>
                                        @endif
                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR]))
                                            <div class="col-md-6">
                                                <a href="{{ route('avaliadoroculto.formularios.create') }}" class="btn btn-flat btn-preto btn-100">Adicionar</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if($user->hasAnyRole(\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE))
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>Consultoria de campo</h4>
                            <hr>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-check-square-o"></i>
                                        <h4>Consultoria de campo</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('admin.consultoria-de-campo') }}" class="btn btn-flat btn-vermelho btn-100">Setup</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($user->hasAnyRole(\App\ACL\Recurso::ADM_METAS))
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>Módulo de Metas</h4>
                            <hr>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-line-chart"></i>
                                        <h4>Módulo de metas</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <a href="{{ route('modulo-de-metas') }}" class="btn btn-flat btn-vermelho btn-100">Metas</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if($user->hasRoles([\App\ACL\Recurso::SUPER_ADMIN]))
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>Registros do sistema</h4>
                            <hr>
                        </div>
                        <div class="col-md-2 col-xs-6">
                            <div class="box box-primary box-menu-admin">
                                <div class="box-body">
                                    <div class="text-center">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <h4>Registro</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ url('logs/accesses') }}" class="btn btn-flat btn-vermelho btn-100">Acessos</a>
                                        </div>
                                        <br/>
                                        <div class="col-md-12" >
                                            <a href="{{ url('logs/modificacoes') }}" class="btn btn-flat btn-preto btn-100">Modificações</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default btn-100" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            var modal;
            $('.open-modal-menu-admin').click(function(){
                $('.modal-menu-admin').modal('show');
            });

            $('.password-mailling').click(function(e){
                e.preventDefault();
                $('[data-dismiss=modal]').trigger('click');
                swal({
                    title: "Acesso restrito",
                    text: "Digite o código de acesso",
                    type: "input",
                    inputType: "password",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Write something"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }

                    window.location.href="{{ url('backend/clientes/listar/excel') }}?password=" + inputValue;
                });
            });
        });
    </script>
@endsection
