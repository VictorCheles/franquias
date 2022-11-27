<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <img src="{{ asset('images/WB.png') }}" class="img-responsive" style="width: 130px;">
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/backend/') }}">Backend</a></li>
                    @if(Auth::user()->nivel_acesso == \App\User::ACESSO_CAIXA)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">BI <small>(Relatórios)</small> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/backend/bi/cupons-por-dia-caixa') }}"><i class="fa fa-ticket"></i>Cupons por dia</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Promoções<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/backend/promocoes/listar') }}"><i class="fa fa-list"></i>Listar</a></li>
                                <li><a href="{{ url('/backend/promocoes/criar') }}"><i class="fa fa-plus"></i>Adicionar</a></li>
                                <li><a href="{{ url('/backend/cupons/buscar') }}"><i class="fa fa-ticket"></i>Buscar cupom</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">BI <small>(Relatórios)</small> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/backend/bi/dashboard') }}"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                                <li><a href="{{ url('/backend/bi/promocoes') }}"><i class="fa fa-line-chart"></i>Promoções</a></li>
                                <li><a href="{{ url('/backend/bi/cupons-por-dia') }}"><i class="fa fa-ticket"></i>Cupons por dia</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ url('/backend/clientes/listar') }}">Clientes</a></li>
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ asset('images/brand_small.png') }}" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->nome_formal }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header" style="background: #961B1E;">
                                <img src="{{ asset('images/brand_small.png') }}" class="img-circle" alt="User Image" style="border: none;">
                                <p>
                                    {{ Auth::user()->nome }}
                                    <small>Entrou no sistema {{ Auth::user()->created_at->diffForHumans() }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ url('/backend/logout') }}" class="btn btn-flat btn-default btn-flat">Sair</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-custom-menu -->
        </div><!-- /.container-fluid -->
    </nav>
</header>