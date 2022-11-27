<?php $user = Auth::user(); ?>
<header class="main-header admin-header">
    <nav class="navbar navbar-static-top nav-black" style="z-index: 1001;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-2" style="height: 75px;">
                    <div class="navbar-header">
                        <a href="{{ url('/') }}" class="navbar-brand flex-center-xs" style="padding-top: 19px;">
                            <img src="{{ asset('/images/0-branco.png') }}" style="height: 40px;">
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 flex-center-xs">
                    <div class="navbar-custom-menu" style="margin-top: 11px;">
                        <ul class="nav navbar-nav">
                            <li style="padding-top: 9px">
                                <form id="demo-2" style="padding-top:0" action="{{ url('busca') }}">
                                    <input type="search1" placeholder="Buscar" name="q">
                                </form>
                            </li>
                            @include('portal-franqueado.partials.notificacoes.comunicados')
                            @include('portal-franqueado.partials.notificacoes.solicitacoes')
                            @include('portal-franqueado.partials.notificacoes.metas')
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown">
                                    <img src="{{ asset('/images/brand-branco.png') }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->primeiro_nome }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header" style="background: #000;">
                                        <img src="{{ Auth()->user()->thumbnail }}" class="img-circle" alt="User Image" style="border: none;">
                                        <p>
                                            {{ Auth::user()->nome }}
                                            <small>Entrou no sistema {{ Auth::user()->created_at->diffForHumans() }}</small>
                                        </p>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{ url('/backend/usuarios/meus-dados') }}" class="btn btn-flat btn-default btn-flat">Meus dados</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ url('/logout') }}" class="btn btn-flat btn-default btn-flat">Sair</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-static-top nav-red" style="min-height: 0;">
        <div class="container">
            <div class="row menu-bg">
                <div class="col-xs-12">
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav navbar-nav-custom margin-menu">
                            <li class="{!! menuActive(collect([url('/')])) !!}"><a href="{{ url('/') }}" style="padding-left: 15px; padding-right: 15px"><i class="glyphicon glyphicon-home"></i> Início</a></li>
                            @include('partials.menu-items.videos')
                            @include('partials.menu-items.comunicados')
                            @include('partials.menu-items.arquivos')
                            @include('partials.menu-items.solicitacoes')
                            @include('partials.menu-items.pedidos')
                            @include('partials.menu-items.metas')
                            @include('partials.menu-items.mensagens')
                            @include('partials.menu-items.administracao')
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
@if($user->isAdmin())
    @include('partials.menu-admin-modal')
@endif

@section('extra_scripts')
    @parent
    <script>
        $(function(){
            var data = ['Arquivos', 'Comunicados', 'Vídeos', 'Solicitações'];
            $('#demo-2 input').typeahead({
                source: data,
                showHintOnFocus: true
            });
        });
    </script>
@endsection