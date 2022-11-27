@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Lista de Formulários
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                    {{--<div class="box-tools pull-right">--}}
                        {{--<a href="#" class="open-modal-filter btn btn-flat btn-box-tool p-2">--}}
                            {{--<button class="btn btn-flat btn-default btn-sm"><i class="fa fa-filter"></i> Filtro</button>--}}
                        {{--</a>--}}
                        {{--<a href="{{ url()->current() }}" class="btn btn-flat btn-box-tool p-2">--}}
                            {{--<button class="btn btn-flat btn-default btn-sm"><i class="fa fa-close"></i> Limpar filtro</button>--}}
                        {{--</a>--}}
                    {{--</div>--}}
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Formulário</th>
                                <th>Tópicos</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->descricao }}</td>
                                    <td>
                                        <ul>
                                            @foreach($item->topicos as $topico)
                                                <li>{{ $topico->descricao }}</li>
                                                <ul>
                                                    @foreach($topico->perguntas as $pergunta)
                                                        <li>{{ $pergunta->pergunta }}</li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="options">
                                        <div class="btn-group-vertical">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR]) and $item->visitas->count() == 0)
                                                <a href="{{ route('admin.consultoria-de-campo.setup.formularios.edit', $item->id) }}" class="btn btn-flat btn-default">
                                                    <i class="fa fa-edit"></i> Editar
                                                </a>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR]) and $item->visitas->count() == 0)
                                                <form class="swal-confirmation" action="{{ route('admin.consultoria-de-campo.setup.formularios.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <a href="#" class="btn btn-flat btn-default fake-submit" style="width: 100%"><i class="fa fa-trash"></i> Deletar</a>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="5">Nenhum registro encontrado</td>
                                @else
                                    <td colspan="5">Nenhum registro cadastrado</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent('form');
                swal({
                        title: "Deletar Formulário?",
                        text: "Esta operação não pode ser desfeita, todas as suas visitas, tópicos e perguntas também serão deletados, deseja continuar?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, deletar Formulário",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            form.submit();
                        } else {
                            return false;
                        }
                    });
                return false;
            });
        });
    </script>
@endsection