@section('extra_styles')
    @parent
    <style>
        .shablaw {
            margin-bottom: 10px;
        }
        .shablaw .btn {
            width: 100%;
        }

        .shablaw .col-xs-12:first-child
        {
            padding-left: 0;
        }

        .shablaw .col-xs-12:last-child
        {
            padding-right: 0;
        }
    </style>
@endsection


<div class="row shablaw">
    @if(!is_null($indicadores))
        @forelse($indicadores as $status_id => $indicador)
            <div class="col-md-2 col-sm-6 col-xs-12">
                {!! \App\Models\Solicitacao::buttonFilter($status_id, $indicador->count()) !!}
            </div>
        @empty
            <div class="col-sm-offset-8"></div>
        @endforelse
    @endif
    @if($auth->isAdmin())
        <div class="col-md-2 col-sm-12 col-xs-12">
            {!! Form::select('filter[user_id]', $solicitantes->pluck('nome', 'id')->toArray(), Request::input('filter.user_id') ,['id' => 'filter-solicitante','placeholder' => 'Filtrar por solicitante','class' => 'form-control select2']) !!}
        </div>
    @endif
    <div class="col-md-2 col-xs-12">
        <a class="btn btn-default" href="{{ url()->current() }}">Limpar filtro</a>
    </div>
</div>

@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('#filter-solicitante').change(function(){
                if(val = $(this).val())
                {
                    var params = $.param({
                        filter: {
                            "user_id" : val
                        }
                    });
                    window.location.href="{{ url()->current() }}?" + params
                }
            });

            $('.select2').select2({
                language: 'pt-BR'
            });
        })
    </script>
@endsection

<div class="row" style="padding-bottom: 10px;">
    <div class="col-xs-12" style="padding: 0;">
        {!! Form::open(['method' => 'get']) !!}
            <div class="input-group input-group-sm" style="width: 100%;">
                <input id="palavra-chave" name="filter[palavra_chave]" class="form-control pull-right" placeholder="Busque sua solicitação aqui..." value="{{ Request::input('filter.palavra_chave') }}">
                <div class="input-group-btn" style="background-color: #fff;">
                    <button type="submit"  class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>