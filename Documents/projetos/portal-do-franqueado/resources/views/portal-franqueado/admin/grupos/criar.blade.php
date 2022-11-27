@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Novo grupo
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
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('admin.grupos.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="row grid">
                        <?php
                        $i = 1;
                        ?>
                        @foreach(\App\ACL\Recurso::RECURSOS_AGRUPADOS as $setor => $recursos)
                            <div class="col-xs-6 grid-item">
                                <div class="box box-black box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{ $i++ . ' - ' . $setor }}</h3>
                                    </div>
                                    <div class="box-body">
                                        @foreach($recursos as $id => $recurso)
                                            <div class="checkbox">
                                                <label>
                                                    {!! Form::checkbox('recurso[]', $id) !!} {{ $recurso }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.grupos.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
    <script>
        $(function(){

            var elem = document.querySelector('.grid');
            var msnry = new Masonry( elem, {
                // options
                itemSelector: '.grid-item',
                columnWidth: 200
            });

            var msnry = new Masonry( '.grid', {
                // options
            });

            $('input[type=checkbox]').first().change(function(){
                if($(this).is(':checked'))
                {
                    swal({
                        title: "{{ $user->primeiro_nome  }}, com grandes poderes vem grandes responsabilidades",
                        text: "<i>Tio Ben</i>",
                        html: true,
                        imageUrl: "{{ asset('images/tio-ben.jpg') }}",
                        imageSize: "200x200"
                    })
                }
            });
        });
    </script>
@endsection