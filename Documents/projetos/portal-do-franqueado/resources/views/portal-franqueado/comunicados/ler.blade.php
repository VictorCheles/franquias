@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="box box-black box-solid no-border">
                <div class="box-body no-padding">
                    <img src="{{ $item->img }}" class="img-responsive" width="100%">
                </div>
                <div class="box-header text-center">
                    <h2>{{ $item->titulo }}</h2>
                </div>
                <div class="text-right">
                    <i><b>{{ $item->created_at->format('d/m/Y H:i:s') }}</b></i>
                </div>
                <div class="box-body">
                    {!! $item->descricao !!}
                </div>
                @if($item->anexos)
                    <div class="box-body">
                        <p><i>Anexos</i></p>
                        <ol>
                            @foreach($item->anexos as $a)
                                <li>
                                    <a target="_blank" href="{{ asset('uploads/comunicados/' . $a) }}">{{ $a }}</a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if(Auth::user()->isAdmin())
                    @include('portal-franqueado.comunicados.questionamentos-admin')
                @else
                    @include('portal-franqueado.comunicados.questionamentos-franqueado')
                @endif

                @if($item->enquete)
                    @include('portal-franqueado.comunicados.responder-enquete')
                @endif
                @if($videos->count() > 0)
                    <div class="box-footer" style="background: #000 !important; color: #fff;">
                        @foreach($videos as $v)
                            {!! \VideoEmbed\VideoEmbed::render($v, ['width' => '100%', 'height' => 350]) !!}
                        @endforeach
                    </div>
                @endif
                @if($item->galeria)
                    @php
                    $galeria = collect($item->galeria);
                    @endphp
                    <div class="row">
                        @foreach($galeria as $g)
                            <div class="col-xs-6 col-sm-3">
                                <a href="{{ asset('uploads/comunicados/' . $g)  }}" class="colorbox">
                                    <img width="150px" height="150px" style="margin-bottom: 15px;" src="{{ asset('uploads/comunicados/' . $g)  }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @if(Auth::user()->isAdmin() and Auth::user()->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_VER_DESTINATARIO]))
                @include('portal-franqueado.comunicados.quem-leu')
            @endif
            @if(Auth::user()->isAdmin() and Auth()->user()->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_EDITAR]))
                <div class="row">
                    <div class="col-xs-12">
                        <a class="btn btn-flat btn-theme-padrao pull-right" href="{{ url('/admin/comunicados/editar', $item->id) }}">Editar este comunicado</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                maxWidth: '80%'
            });
        });
    </script>
@endsection