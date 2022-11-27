<?php $auth = Auth()->user(); ?>
@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Canal do franqueado
                </h1>
                <h1 class="section-title caticon sbx pull-right">
                    {!! Form::open(['method' => 'get']) !!}
                    <div class="row">
                        <div class="col-sm-9">
                            {!! Form::text('q', Request::get('q'), ['placeholder' => 'buscar por título ou tag', 'class' => 'form-control typehead-field', 'autocomplete' => 'off']) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::submit('Buscar', ['class' => 'btn btn-theme-padrao btn-min-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </h1>
            </div>
        </section>
    </div>
    @foreach($lista as $t)
        <div class="row">
            <section class="col-xs-12">
                <div class="col-sm-3 {{ $t->quemAssistiu()->where('user_id', $auth->id)->count() > 0 ? 'video-assistido' : ''}}">
                    <div class="img-container">
                        <label class="label hide">Assistido</label>
                        <img src="{{ $t->thumbnail }}" class="img-responsive" style="margin-bottom: 10px; width: 100%">
                    </div>
                </div>
                <div class="col-sm-9">
                    <label class="label" style="background: {{ $t->tag->cor }}">{{ $t->tag->titulo }}</label>
                    <h4>{{ $t->titulo }}</h4>
                    {!! $t->descricao !!}
                    <a class="btn btn-primary btn-sm pull-right" href="{{ route('video.show', $t->id) }}"><i class="fa fa-video-camera"></i> Assistir</a>
                </div>
            </section>
        </div>
    @endforeach
    <div class="center pagination-black">
        {{ $lista->appends(Request::all())->links() }}
    </div>
@endsection
@section('extra_scripts')
    @parent
    <?php $caralha = \App\Models\Tag::select('titulo')->orderBy('titulo', 'asc')->lists('titulo')->implode(','); ?>
    <script>
        $(function(){
            var data = "{!! $caralha !!}".split(',');
            $(".typehead-field").typeahead({
                source: data,
                showHintOnFocus: true
            });
        });
    </script>
@endsection