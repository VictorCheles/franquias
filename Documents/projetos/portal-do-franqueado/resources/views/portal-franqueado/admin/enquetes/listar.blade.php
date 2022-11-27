@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Enquetes
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
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->inicio->diffForHumans() }}</td>
                                    <td>{{ $item->fim->diffForHumans() }}</td>
                                    <td>{!! $item->aberta_formatted !!}</td>
                                    <td class="options">
                                        <div class="row">
                                            <div class="col-md-3"> 
                                                <a href="{{ route('enquetes.responder', $item->id) }}"  class="btn btn-success" style="width: 100%"><i title="Responder" class="fa fa-check-square-o"></i></a>
                                            </div>

                                            <div class="col-md-3"> 
                                                <a href="#"  class="btn btn-default open-modal" data-rel="{{ $item->id }}" style="width: 100%"><i title="Ver Perguntas" class="fa fa-eye"></i></a>
                                            </div>

                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ENQUETES_VER_RESULTADOS]))
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('admin.enquetes.show', $item->id) }}"  class="btn" style="width: 100%; background-color: #3c8dbc; color: #fff"><i title="Resultados da Enquete" class="fa fa-info"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ENQUETES_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.enquetes.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-3"> 
                                                        <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="4">Nenhuma enquete encontrada</td>
                                @else
                                    <td colspan="4">Nenhuma enquete cadastrada</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="text-center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('portal-franqueado.admin.enquetes.modals.perguntas')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '50%'
            });

            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar enquete?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar enquete",
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