@extends('layouts.app')
@section('content')
    <div class="row">
        {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">Buscar cupons</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {!! Form::label('code', 'Código') !!}
                            {!! Form::text('filter[code]' , Request::input('filter.code') , ['id' => 'code','class' => 'form-control', 'data-mask' => '***-***']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('promocao', 'Promoção') !!}
                            {!! Form::select('filter[promocao]' , \App\Models\Promocao::orderBy('nome')->lists('nome', 'id') , Request::input('filter.promocao'), ['placeholder' => 'Selecione uma opção','class' => 'form-control chosen']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('filter[status]' , \App\Models\Cupom::$mapsStatus , Request::input('filter.status'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(url()->current(), 'Limpar', ['class' => 'btn btn-flat btn-default', 'data-dismiss' => 'modal']) !!}
                    {!! Form::submit('Filtrar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Promoção</th>
                                <th>Código</th>
                                <th>Código Original</th>
                                <th>Quem emitiu</th>
                                <th>Quem validou</th>
                                <th>Status</th>
                                <th>Validade</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista and $lista->count() > 0)
                            @foreach($lista as $item)
                                <?php $quemEmitiu = $item->cliente(); ?>
                                <?php $quemResgatou = $item->user(); ?>
                                <tr>
                                    <td>{{ $item->promocao()->first()->nome }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->codigo_original }}</td>
                                    <td>{!! $quemEmitiu->count() > 0 ? $quemEmitiu->first()->nome . '<br>' . $quemEmitiu->first()->email : '' !!}</td>
                                    <td>{!! $item->loja_id ? $item->loja->nome . '<br>' . $item->user->nome : '' !!}</td>
                                    <td>{!! $item->status_formatted !!}</td>
                                    <td>{{ $item->validade_cupom->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Nenhum cupom encontrado</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        @if($lista and (get_class($lista) == Illuminate\Pagination\LengthAwarePaginator::class))
                            {{ $lista->appends(Request::all())->links() }}
                        @endif
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(function(){
            $('.chosen').chosen({
                no_results_text: "Sem resultados para",
                placeholder_text_single: "Selecione uma opção",
                placeholder_text_multiple: "Selecione os destinatários"
            });
        });
    </script>
@endsection()