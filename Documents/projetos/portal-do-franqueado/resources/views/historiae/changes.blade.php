@extends('layouts.portal-franqueado-full-width')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Registro de modificações
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
                            <th>Modelo</th>
                            <th>Chave primária</th>
                            <th>Novo?</th>
                            <th>Payload</th>
                            <th>Usuário</th>
                            <th>Horário</th>
                        </tr>
                        </thead>
                        @if($changes->count())
                            @foreach($changes as $change)
                                <tr>
                                    <td>{{ $change->model }}</td>
                                    <td>{{ $change->created ? "-" : $change->json['id'] }}</td>
                                    <td>@if($change->created) Sim @else Não @endif</td>
                                    <td>
                                        <a href="#" class="btn btn-flat btn-info open-modal-payload" data-payload="{{ $change->id }}">Ver Payload</a>
                                    </td>
                                    <td>@if($change->user){{ $change->user->nome }}@endif</td>
                                    <td>{{ $change->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        {{ $changes->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('historiae.modals.changes-filter')
    @include('historiae.modals.changes-payload')
@endsection
