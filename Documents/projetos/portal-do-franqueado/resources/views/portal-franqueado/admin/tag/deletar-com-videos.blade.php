@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Vídeos</h3>
                </div>
                <div class="box-body">
                    <p>Esta Tag contem os seguintes vídeos</p>
                    <ul>
                        @foreach($item->videos as $video)
                            <li><a href="{{ route('video.show', $video->id) }}" target="_blank">{{ $video->titulo }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @if($user->hasRoles([\App\ACL\Recurso::ADM_TAGS_VIDEOS_REMANEJAR]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-remanejamento">
                                Remanejamento de vídeos
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-remanejamento" class="panel-collapse collapse">
                        @if($tags->count() > 0)
                            {!! Form::open(['url' => route('admin.tag.destroy.remanejamento', $item->id), 'class' => 'swal-confirmation-2']) !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="box-body">
                                <div class="form-group">
                                    {!! Form::label('tag_id', 'Tag') !!}
                                    {!! Form::select('tag_id' , $tags, '', ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                {!! link_to('#', 'Remanejar vídeos', ['class' => 'btn btn-flat btn-primary fake-submit-2']) !!}
                                {!! link_to(route('admin.tag.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="box-body">
                                <p>O remanejamento não pode ser feito, pois não existe outra tag alem desta cadastrada no sistema.</p>
                                <p><a href="{{ route('admin.tag.create') }}">Clique aqui para cadastrar outras tags</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($user->hasRoles([\App\ACL\Recurso::ADM_TAGS_VIDEOS_DELETAR]))
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <a style="color: #fff;" data-toggle="collapse" data-parent="#accordion" href="#collapse-deletar">
                                Deletar vídeos desta tag
                            </a>
                        </h3>
                    </div>
                    <div id="collapse-deletar" class="panel-collapse collapse">
                        {!! Form::open(['url' => route('admin.tag.destroy.cascade', $item->id), 'class' => 'swal-confirmation']) !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="box-body">
                            <p>Esta opção, vai deletar a tag e todos seus vídeos vinculados</p>
                        </div>
                        <div class="box-footer">
                            {!! link_to('#', 'Deletar', ['class' => 'btn btn-flat btn-primary fake-submit']) !!}
                            {!! link_to(route('admin.pasta.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
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
                    title: "Deletar tag e seus vídeos?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar tag e vídeos",
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
                    title: "Deletar tag e remanejar seus vídeos?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar tag e seus vídeos",
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