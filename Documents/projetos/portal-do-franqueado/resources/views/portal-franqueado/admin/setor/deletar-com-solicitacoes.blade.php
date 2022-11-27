@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Solicitações</h3>
                </div>
                <div class="box-body">
                    <p>Este setor contem as seguintes solicitações</p>
                    <ul>
                        @foreach($item->solicitacoes as $solicitacao)
                            <li><a href="{{ route('solicitacao.show', $solicitacao->id) }}" target="_blank">{{ $solicitacao->titulo }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if($user->hasRoles([\App\ACL\Recurso::ADM_SETORES_REMANEJAR_SOLICITACOES]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-remanejamento">
                                Remanejamento de solicitações
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-remanejamento" class="panel-collapse collapse">
                        @if($setores->count() > 0)
                            {!! Form::open(['url' => route('admin.setor.destroy.remanejamento', $item->id), 'class' => 'swal-confirmation-2']) !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="box-body">
                                <p>Esta opção vai remanejar as solicitações para outro setor</p>
                                <div class="form-group">
                                    {!! Form::label('setor_id', 'Setor') !!}
                                    {!! Form::select('setor_id' , $setores, '', ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                {!! link_to(route('admin.setor.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                                {!! link_to('#', 'Remanejar solicitações', ['class' => 'btn btn-flat btn-primary pull-right fake-submit-2']) !!}
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="box-body">
                                <p>O remanejamento não pode ser feito, pois não existe outro setor alem deste cadastrada no sistema.</p>
                                <p><a href="{{ route('admin.setor.create') }}">Clique aqui para cadastrar outros setores</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($user->hasRoles([\App\ACL\Recurso::ADM_SETORES_DELETAR_SOLICITACOES]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-deletar">
                                Deletar solicitações deste setor
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-deletar" class="panel-collapse collapse">
                        {!! Form::open(['url' => route('admin.setor.destroy.cascade', $item->id), 'class' => 'swal-confirmation']) !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="box-body">
                            <p>Esta opção vai deletar o setor e todas suas solicitações vinculadas</p>
                        </div>
                        <div class="box-footer">
                            {!! link_to(route('admin.setor.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                        {!! link_to('#', 'Deletar', ['class' => 'btn btn-flat btn-primary pull-right fake-submit']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar setor e seas solicitações?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar setor e solicitações",
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
            $('.swal-confirmation-2 .fake-submit-2').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar setor e remanejar suas solicitações?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar setor e remanejar suas solicitações",
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