@extends('layouts.app-avaliador-oculto')
@section('extra_styles')
    <style>
        .color-red
        {
            color: #7D1A1D;
        }
        .amarelo
        {
            border: 1px solid #FABA0A;
        }
        p.color-red
        {
            font-size: 20px;
        }
        .color-yellow
        {
            color: #FABA0A;
        }
    </style>
@endsection
@section('content')
    <h2 class="color-red text-center" style="font-weight: bold;">Avaliação concluída com sucesso!</h2>
    <hr class="amarelo">
    <p class="color-red text-center">
        Mais uma vez agradecemos por ter chegado até aqui e deixado observações relevantes para o nosso crescimento.
    </p>
    <p class="color-red text-center">
        Tire uma foto do cupom fiscal e faça o upload da imagem para que a gente possa fazer o seu ressarcimento em até 5 dias úteis.
    </p>
    <br>
    {!! Form::open(['id' =>'submit-me', 'url' => route('enviar.comprovante.post', [$formulario_id, $loja_id]), 'files' => true]) !!}
    <div class="box-body">
        <input type="hidden" name="formulario_id" value="{{ $formulario->id }}">
        <input type="hidden" name="loja_id" value="{{ $loja->id }}">
        <div class="form-group">
            <label for="imagem">Upload do cupom fiscal</label>
            {!! Form::file('comprovante' , ['id' => 'comprovante','required']) !!}
        </div>
    </div>
    <div class="box-footer">
        <a href="#" class="btn btn-flat btn-primary fake-submit">Enviar</a>
    </div>
    {!! Form::close() !!}
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ asset('images/icone-pagamento-ok-2.png') }}" class="img-responsive" style="width: 150px; margin:0 auto;">
                </div>
                <div class="modal-header text-center">
                    <h4 class="modal-title">O seu comprovante foi enviado com sucesso</h4>
                </div>
                <div class="modal-body">
                    <p>Qualquer dúvida entre em contato conosco através do <b>{{ env('EMAIL_CLIENTE_OCULTO') }}</b></p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-flat btn-info real-submit" style="width: 100%">Clique aqui para finalizar</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(function(){
            $('.fake-submit').click(function(e){
                e.preventDefault();
                if( document.getElementById("comprovante").files.length == 0 ){
                    swal('Comprovante não anexado', 'O envio do comprovante é obrigatório', 'warning');
                }
                else
                {
                    $('.modal').modal();
                }
            });
            
            $('.real-submit').click(function(e){
                e.preventDefault();
                $('#submit-me').submit();
            });
        });
    </script>
@endsection