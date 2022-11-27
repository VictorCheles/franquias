@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $cor = 'box-black'; ?>
@else
    <?php $layout = 'layouts.app'; $cor = 'box-danger';?>
@endif

@extends($layout)

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Meus dados - {{ $item->nome }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box {{ $cor }} box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Editar</h3>
                </div>
                {!! Form::open(['url' => url()->current(), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group">
                        @if($item->foto)
                            <a href="{{ $item->thumbnail }}">
                                <img class="img-responsive" style="width: 50%" src="{{ $item->thumbnail }}">
                            </a>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , $item->nome , ['readonly', 'disabled' ,'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('foto') ? 'has-error' : '' }}">
                        {!! Form::label('foto', 'Foto') !!}
                        {!! Form::file('foto') !!}
                        <a href="#" class="open-modal-foto">Clique aqui para ver as instruções</a>
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', 'E-mail') !!}
                        {!! Form::email('email' , $item->email , ['readonly', 'disabled', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('loja_id', 'Franquia') !!}
                        {!! Form::select('loja_id' , \App\Models\Loja::lists('nome', 'id')->sortBy('nome') , $item->lojas->pluck('id')->toArray(), ['readonly', 'disabled', 'class' => 'form-control select2', 'multiple' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', 'Nova Senha') !!}
                        {!! Form::password('password' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirme a nova senha') !!}
                        {!! Form::password('password_confirmation' , ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group {{ $errors->has('original_password') ? 'has-error' : '' }}">
                        {!! Form::label('original_password', 'Digite sua senha atual') !!}
                        {!! Form::password('original_password' , ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to('/backend/usuarios/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="modal modal-foto fade">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Instruções para foto</h4>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('images/exemplofoto.png') }}" class="img-responsive">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Fechar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-foto').click(function(e){
                e.preventDefault();
                $('.modal-foto').modal();
            });

            $('.select2').select2({
                language: 'pt-BR'
            });
        });
    </script>
@endsection