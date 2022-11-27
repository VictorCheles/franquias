@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Pasta com Arquivos! - {{ $item->nome }}
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
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Arquivos</h3>
                </div>
                <div class="box-body">
                    <p>Esta pasta contem os seguintes arquivos</p>
                    <ul>
                        @foreach($item->arquivos as $arquivo)
                            <li><a href="{{ $arquivo->arquivo_src }}" target="_blank">{{ $arquivo->nome }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if($user->hasRoles([\App\ACL\Recurso::ADM_PASTAS_ARQUIVOS_REMANEJAR]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-remanejamento">
                                Remanejamento de arquivos
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-remanejamento" class="panel-collapse collapse">
                        @if(count($listKv) > 0)
                            {!! Form::open(['url' => route('admin.pasta.destroy.remanejamento', $item->id), 'class' => 'swal-confirmation-2']) !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="box-body">
                                <div class="form-group">
                                    {!! Form::label('pasta_id', 'Pasta') !!}
                                    {!! Form::select('pasta_id' , $listKv, '', ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                {!! link_to(route('admin.pasta.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                                {!! link_to('#', 'Remanejar arquivos', ['class' => 'btn btn-flat btn-primary pull-right fake-submit-2']) !!}
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="box-body">
                                <p>O remanejamento não pode ser feito, pois não existe outra pasta alem desta cadastrada no sistema.</p>
                                <p><a href="{{ route('admin.pasta.create') }}">Clique aqui para cadastrar outras pastas</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($user->hasRoles([\App\ACL\Recurso::ADM_PASTAS_ARQUIVOS_DELETAR]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-deletar">
                                Deletar arquivos desta pasta
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-deletar" class="panel-collapse collapse">
                        {!! Form::open(['url' => route('admin.pasta.destroy.cascade', $item->id), 'class' => 'swal-confirmation']) !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="box-body">
                            <p>Esta opção, vai deletar a pasta e todos seus arquivos vinculados</p>
                        </div>
                        <div class="box-footer">
                            {!! link_to(route('admin.pasta.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
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
                    title: "Deletar pasta e seus arquivos?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar pasta e arquivos",
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
                    title: "Deletar pasta e remanejar seus arquivos?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar pasta e remanejar seus arquivos",
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