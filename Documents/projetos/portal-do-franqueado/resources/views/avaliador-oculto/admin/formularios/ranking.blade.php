@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Ranking
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Form::open(['url' => url()->current() ,'method' => 'get']) !!}
            <div class="form-group {{ $errors->has('responsavel') ? 'has-error' : '' }}">
                {!! Form::label('formularios', 'Formulários') !!}
                <select class="form-control select2" name="formularios[]" multiple>
                    @foreach($formularios_filter as $k => $v)
                        @if(!is_null(Request::get('formularios')) and in_array($k, Request::get('formularios')))
                            <option selected value="{{ $k }}">{{ $v }}</option>
                        @else
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!! link_to(url()->current(), 'Limpar Ranking', ['class' => 'btn btn-flat btn-default']) !!}
                {!! Form::submit('Gerar Ranking', ['class' => 'btn btn-info btn-flat pull-right']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="6%">Ranking</th>
                                <th>Loja</th>
                                <th>Visitas</th>
                                <th>Score Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!is_null($lojas))
                                <?php $ranking = 1;?>
                                @foreach($lojas as $loja)
                                    <?php
                                    $class = '';
                                    if ($ranking == count($lojas)) {
                                        $class = 'ultimo';
                                    }
                                    ?>
                                    <tr class="{{ $class }}">
                                        <td class="text-center">{!! rankingTrophies($ranking++) !!}</td>
                                        <td>{{ $loja['loja'] }}</td>
                                        <td>{{ count($loja['scores_individuais']) }}</td>
                                        <td>{{ number_format($loja['score_medio'], 2, ',', '.') }}%</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.select2').select2({
                language: 'pt-BR'
            });
        });
    </script>
@endsection