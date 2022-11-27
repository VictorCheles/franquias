<div class="box-body">
    <div class="col-xs-12">
        <h4>Adicionar a imagem</h4>
    </div>
    <div class="col-md-6 col-xs-12">
        @if($imagens_padrao->count() > 0)
            <div class="slider-for">
                <img src="https://placehold.it/529x238?text=Selecione uma imagem" class="img-responsive img-thumbnail">
                @foreach($imagens_padrao as $imagem)
                    <img src="{{ $imagem->thumbnail }}" class="img-responsive img-thumbnail">
                @endforeach
            </div>
            <div class="slider-nav">
                <label>
                    <img src="https://placehold.it/70x70?text=Selecione uma imagem" class="img-responsive img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                    <input style="display: none;" type="radio" name="imagem_id" value>
                </label>
                @foreach($imagens_padrao as $imagem)
                    <label>
                        <img src="{{ $imagem->thumbnail }}" class="img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                        <input style="display: none;" type="radio" name="imagem_id" value="{{ $imagem->id }}">
                    </label>
                @endforeach
            </div>
        @else
            <img src="https://placehold.it/529x238?text=Nenhuma Imagem padrão cadastrada" class="img-responsive img-thumbnail">
        @endif
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="form-group">
            <i class="fa fa-file-image-o"></i>
            {!! Form::label('imagem', 'Carregar nova imagem') !!}
            {!! Form::file('imagem') !!}
        </div>
        <div class="form-group">
            <i class="fa fa-picture-o"></i>
            {!! Form::label('galeria', 'Carregar nova imagem para galeria') !!}
            {!! Form::file('galeria[]', ['multiple' => true]) !!}
        </div>
        <div class="form-group">
            <i class="fa fa-paperclip"></i>
            {!! Form::label('anexos', 'Anexos(Ex: pdf, txt)') !!}
            {!! Form::file('anexos[]', ['multiple' => true]) !!}
        </div>
    </div>
</div>
<hr>