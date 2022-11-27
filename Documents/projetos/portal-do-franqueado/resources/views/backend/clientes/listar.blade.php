
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn btn-flat btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn btn-flat btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                        <a href="{{ url('backend/clientes/listar/excel') }}" class="btn btn-flat btn-box-tool password-mailling">
                            <i class="fa fa-file-excel-o"></i> Baixar Mailling Cupons
                        </a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th colspan="2"></th>
                                <th colspan="4" class="text-center" style="border-left: 2px solid #f4f4f4;border-right: 2px solid #f4f4f4;">Cupons</th>
                                <th colspan="3" class="text-center" style="border-left: 2px solid #f4f4f4;border-right: 2px solid #f4f4f4;">Acesso</th>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Gerados</th>
                                <th>Usados</th>
                                <th>Válidos</th>
                                <th>Vencidos</th>
                                <th>Primeiro</th>
                                <th>Ultimo</th>
                                {{--<th>Opções</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <?php
                                $cupons = $item->cupons()->get();
                                $total = $cupons->count();
                                $usados = collect();
                                $validos = collect();
                                $vencidos = collect();
                                $cupons->each(function ($i) use ($validos, $usados, $vencidos) {
                                    if ($i->status() == \App\Models\Cupom::STATUS_USADO) {
                                        $usados->push($i);
                                    }
                                    if ($i->status() == \App\Models\Cupom::STATUS_VALIDO) {
                                        $validos->push($i);
                                    }
                                    if ($i->status() == \App\Models\Cupom::STATUS_VENCIDO) {
                                        $vencidos->push($i);
                                    }
                                });
                                ?>
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $cupons->count() }}</td>
                                    <td>{{ $usados->count() }}</td>
                                    <td>{{ $validos->count() }}</td>
                                    <td>{{ $vencidos->count() }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $item->updated_at->diffForHumans() }}</td>
                                    <td class="options">
                                        {{--<a href="{{ url('/backend/usuarios/editar', $item->id) }}" class="btn btn-flat btn-app">--}}
                                            {{--<i class="fa fa-edit"></i> Editar--}}
                                        {{--</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="5">Nenhum cliente encontrado</td>
                                @else
                                    <td colspan="5">Nenhum cliente cadastrado</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('backend.clientes.modals.filtro')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.password-mailling').click(function(e){
                e.preventDefault();
                swal({
                    title: "Acesso restrito",
                    text: "Digite o código de acesso",
                    type: "input",
                    inputType: "password",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Write something"
                },
                function(inputValue){
                    if (inputValue === false) return false;

                    if (inputValue === "") {
                        swal.showInputError("You need to write something!");
                        return false
                    }
                    window.location.href="{{ url('backend/clientes/listar/excel') }}?password=" + inputValue;
                });
            });
        });
    </script>
@endsection