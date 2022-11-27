@section('extra_styles')
    @parent
    <style>
        .container-post-title
        {
            padding: 6px;
        }


        .post-title
        {
            /*text-shadow: 0 1px 3px rgba(255,255,255,.8);*/
            font-size: 21px;
            margin: 5px 0 0 0;
        }

        .box-post {
            position: absolute;
            bottom: 0;
            padding: 0;
        }

        .card {
            padding: 0;
            cursor: pointer;
        }

        .wrapper
        {
            transition: -webkit-transform .35s ease;
            transition: transform .35s ease;
            transition: transform .35s ease,-webkit-transform .35s ease;
        }

        .card:hover .wrapper
        {
            -webkit-transform: scale(1.1);
            transform: scale(1.1);
            background-color: #000;
            background-image: inherit;
            background-position: 50%;
            background-size: cover;
        }

        .dual .card:first-child {
            margin-bottom: 20px;
        }

        .label-wb a
        {
            color: #fff;
            display: inline;
        }

        .label-wb {
            background-color: #6f1418;
            border-radius: 0;
        }

        .box-post span.meta {
            display: inline;
            margin-left: 1px;
            background: #d70000;
        }

        .comunicados .wrapper {
            text-transform: uppercase;
        }
    </style>
@endsection

@if($comunicados->count() > 0)
    <section class="col-xs-12 comunicados">
        <div class="headline-row">
            <h1 class="section-title caticon sbx">
                <img src="{{ asset('images/brand_small.png') }}"> Comunicados
            </h1>
        </div>
        <div class="row">
            @php
                $first = $comunicados->shift();
            @endphp
            <div class="col-xs-12 col-md-8">
                <div onclick="window.location.href='{{ url('/comunicados/ler', $first->id) }}'" class="card" style="height: 450px;">
                    <div class="wrapper" style="background: url('{{ $first->img }}') center/cover no-repeat;">
                        <div class="box-post">
                            <div style="display: initial; padding: 0 0 1px 0;">
                                <span class="label label-wb">
                                    <a href="{{ url('/comunicados/ler', $first->id) }}" rel="tag">{{ $first->setor->nome }}</a>
                                </span>
                                <span class="meta label label-wb">
                                    <i class="glyphicon glyphicon-time"></i> {{ $first->created_at->formatLocalized('%b') }} {{ $first->created_at->format('d') }}
                                </span>
                            </div>
                            <div style="background: #4a4a4a; padding: 6px 6px 6px 6px;">
                                <h1 class="post-title">
                                    <a style="color: #fff;" href="{{ url('/comunicados/ler', $first->id) }}">
                                        {{ $first->titulo }}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
            $i = 0;
            @endphp
            @foreach($comunicados as $comunicado)
                @php
                $class = 'col-md-6';
                if($i == 0 or $i == 1)
                    $class = 'col-md-4';
                @endphp
                <div class="col-xs-12 {{ $class }}">
                    <div onclick="window.location.href='{{ url('/comunicados/ler', $comunicado->id) }}'" class="card" style="height: 210px;">
                        <div class="wrapper" style="background: url('{{ $comunicado->img }}') center/cover no-repeat;">
                            <div class="box-post">
                                <div style="display: initial; padding: 0 0 1px 0;">
                                    <span class="label label-wb">
                                        <a href="{{ url('/comunicados/ler', $comunicado->id) }}" rel="tag">{{ $comunicado->setor->nome }}</a>
                                    </span>
                                    <span class="meta label label-wb">
                                        <i class="glyphicon glyphicon-time"></i> {{ $comunicado->created_at->formatLocalized('%b') }} {{ $comunicado->created_at->format('d') }}
                                    </span>
                                </div>
                                <div style="background: #4a4a4a; padding: 6px 6px 6px 6px;">
                                    <h1 class="post-title">
                                        <a style="color: #fff;" href="{{ url('/comunicados/ler', $comunicado->id) }}">
                                            {{ $comunicado->titulo }}
                                        </a>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="btn btn-theme-padrao btn-sm btn-min-block pull-right" style="margin-right: 0%" href="{{ url('comunicados/listar') }}"><b>Ver mais</b></a>
    </section>
@endif