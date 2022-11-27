@extends('layouts.portal-franqueado-full-width')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Registro de acessos
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
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Domínio</th>
                            <th>Caminho</th>
                            <th>Método</th>
                            <th>Status</th>
                            <th>Endereço IP</th>
                            <th>Usuário</th>
                            <th>Horário</th>
                        </tr>
                        </thead>
                        @if($accesses->count())
                            @foreach($accesses as $access)
                                <tr>
                                    <td>{{ str_limit($access->domain, $limit = 80, $end = '...') }}</td>
                                    <td>{{ str_limit($access->url, $limit = 80, $end = '...') }}</td>
                                    <td>{{ $access->method }}</td>
                                    <td>{{ $access->status }}</td>
                                    <td>{{ $access->ip }}</td>
                                    <td>@if($access->user){{ $access->user->nome }}@endif</td>
                                    <td>{{ $access->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        {{ $accesses->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('historiae.modals.accesses-filter')
@endsection