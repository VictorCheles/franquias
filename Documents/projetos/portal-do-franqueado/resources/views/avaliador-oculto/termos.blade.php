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
    <h2 class="color-red text-center" style="font-weight: bold;">Olá {{ Auth::guard('avaliador_oculto')->user()->nome }}</h2>
    <hr class="amarelo">
    <p class="color-red text-center">
        Seja bem vindo ao <b>Programa Cliente Oculto {{ env('APP_NAME') }}!</b>
    </p>
    <br>
    <p class="color-red text-center">
        Você foi selecionado entre vários candidatos para integrar ao time de avaliadores ocultos {{ env('APP_NAME') }}<br>

        {{--Agradecemos pela participação (e sigilo!) nesse projeto que tem como objetivo o aperfeiçoamento de nossos produtos para quem realmente importa: nossos clientes.--}}
    </p>
    <br>
    <p class="text-center">
        <img src="{{ asset('images/top-secret.png') }}" class="img-responsive" style="margin: 0 auto;">
    </p>
    <br>
    <div class="form-group">
        <div class="col-sm-12">
            <a href="#" class="open-modal-filter" style="color: #333;"><i style="text-decoration: underline">Clique aqui para ler o Termo de Compromisso</i></a>
        </div>
    </div>
    <form class="form-horizontal" action="{{ route('postIndex') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="col-sm-12">
                <label style="font-weight: normal;">
                    <input style="width: auto;" type="checkbox" name="aceite" value="1" required> Li e aceito os temos e condições.
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-flat btn-success" style="width: 100%;">Avançar</button>
    </form>
    <div class="modal modal-filter fade">
        <div class="modal-dialog" style="width: 95% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Termo de Compromisso</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-xs-12">
                            <p class="text-justify">
                                Pelo presente instrumento particular de um lado
                                <b>FAL COMERCIO DE ALIMENTOS LTDA– ME</b>,
                                com sede no Natal, Rio Grande do Norte, Rua Miguel
                                castro, 1503 – Lagoa Nova – Cep 59075-740, no CNPJ/MF 09.665.609/0001-
                                54, representada, neste ato, na forma de seu contrato social, doravante
                                denominado simplesmente FAL COMERCIO e, de outro lado, o Sr.
                                {{ Auth::guard('avaliador_oculto')->user()->nome }},
                                brasileiro, estado civil, profissão, portadora da
                                Cédula de Identidade RG nº {{ Auth::guard('avaliador_oculto')->user()->rg }}
                                SSP/{{ Auth::guard('avaliador_oculto')->user()->ssp }}, inscrito no
                                CPF/MF sob o nº {{ Auth::guard('avaliador_oculto')->user()->cpf }}, residente e domiciliado
                                {{ Auth::guard('avaliador_oculto')->user()->endereco }}, doravante denominado CLIENTE OCULTO, os
                                quais resolvem, em comum acordo, celebram o presente Termo que se regerá
                                pelas seguintes condições:
                            </p>
                            <br>
                            <h4>CLÁUSULA 1ª – DO OBJETO:</h4>
                            <p class="text-justify">
                                O objeto do presente termo consiste na fiscalização dos serviços e alimentos
                                ofertados aos seus consumidores, com o fim de verificar a regularidade ou não
                                do padrão {{ env('APP_NAME_FULL') }}, e através das informações colhidas
                                identificar as necessidades, para assim aperfeiçoar o desempenho da rede
                                {{ env('APP_NAME_FULL') }}, visando ganhos operacionais de qualidade,
                                produtividade e crescimento do negócio, de forma a garantir a satisfação dos
                                clientes.
                            </p>
                            <br>
                            <h4>CLÁUSULA 2ª – DAS OBRIGAÇÕES:</h4>
                            <p class="text-justify">
                                <b>2.1</b> O cliente após inscrever-se no site, será selecionado, e então contatado
                                para participar do Projeto CLIENTE OCULTO.
                                <br>
                                <b>2.2</b> Ao aceitar participar do referido Projeto, após passar por seleção, e ser
                                devidamente contactado, o CLIENTE OCULTO estará efetivamente vinculado
                                as obrigações aqui contratadas, portanto, todas as informações obtidas através
                                do projeto e função - CLIENTE OCULTO, serão sigilosas e confidenciais, sob
                                pena de responsabilizar-se civil e criminalmente pelos danos que causar à FAL
                                COMÉRCIO, de forma que o cliente deverá tão somente restringir-se ao
                                preenchimento do formulário de visita no sitio
                                <b>{{ url('/') }}</b>.
                                <br>
                                <b>2.3</b> O CLIENTE OCULTO deve comparecer em uma das Lojas Franqueadas
                                {{ env('APP_NAME') }}, as quais estão listadas no Termo de Pesquisa e Avaliação de
                                Atendimento, preencher o formulário de visita no seu próprio aparelho celular,
                                via on line, bem como, responder todos os quesitos constantes no TERMO,
                                através do sistema disponível no sitio <b>{{ url('/') }}</b>.
                                <br>
                                <b>2.4</b> O CLIENTE OCULTO ao realizar a avaliação dos quesitos constantes no
                                TERMO poderá optar pelo consumo dos produtos pré-determinados, tais como
                                – Burgers, sobremesa, pratos executivos, petiscos e bebidas.
                                <br>
                                <b>2.5</b> Em hipótese alguma, o CLIENTE OCULTO poderá informar ou divulgar o
                                desenvolvimento da referida ação voluntária, ou até mesmo demonstrar, deixar
                                transparecer a qualquer terceiro, funcionário ou pessoa diversa da FAL
                                COMERCIO, visando não comprometer a intensão e objetivo da ação.
                                <br>
                                <b>2.6</b> O formulário de visita deve ser preenchido com total veracidade dos fatos
                                encontrados na Loja Franqueada, devendo responder todas as perguntas
                                constantes no questionário.
                            </p>
                            <br>
                            <h4>CLÁUSULA 3º - DAS DESPESAS:</h4>
                            <p class="text-justify">
                                O Cliente aderente ao projeto “CLIENTE OCULTO” terá direito a uma cortesia no valor de R$ 35,00 (trinta e cinco reais) após cumpridas todas as exigências deste instrumento.
                                <br>
                                <br>
                                <b>3.1</b>A FAL COMERCIO não irá ressarcir o cliente em valor superior ao descrito acima. Acaso seja consumido valor superior, a diferença deverá ser custeada pelo CLIENTE OCULTO, o que fica ciente e declara o “aceite” através deste instrumento.
                                <br>
                                <b>3.2</b> O ressarcimento da quantia descrita no caput da presente cláusula deverá ser solicitado através do sitio {{ url('/') }}, onde o CLIENTE OCULTO irá realizar o UPLOAD da nota fiscal de consumo, e, após 5 dias uteis, o cliente será ressarcido através de transferência bancária, de acordo com os dados bancários informados na ficha cadastral, no valor limite de até R$ 35,00 (trinta e cinco reais).
                            </p>
                            <br>
                            <h4>CLAUSULA 4ª – PENALIDADES:</h4>
                            <p class="text-justify">
                                Em caso de descumprimento de quaisquer cláusulas do presente termo, o CLIENTE OCULTO responderá civil e criminalmente pelos prejuízos que causar à FAL COMERCIO, podendo ainda responder por perdas e danos.
                            </p>
                            <br>
                            <h4>CLAUSULA 5ª – DAS DISPOSIÇÕES GERAIS:</h4>
                            <p class="text-justify">
                                <br>
                                <b>5.1</b> No ato de aceite do presente termo, declara o CLIENTE OCULTO ter total conhecimento e concordar com todas as cláusulas constantes no presente, e não se opõe a nenhuma de suas cláusulas, termos e obrigações.
                                <br>
                                <b>5.2</b> A FAL COMERCIO declara que a presenta ação se refere a uma ação voluntária, a qual não gera qualquer vínculo empregatício do CLIENTE OCULTO para com a empresa.
                                <br>
                                <b>5.3</b> Declara ainda o aceite em participar da presente ação voluntária, a qual não gera qualquer vínculo, cível, trabalhista, ou outro que possa existir, para com a FAL COMERCIO, sem quaisquer remuneração para tanto.
                                <br>
                                <b>5.4</b> Em caso de falecimento, de qualquer das partes, seus herdeiros se obrigam a cumprir e respeitar o presente Termo em todas as suas cláusulas e condições.
                                <br>
                                <b>5.5</b> O presente termo é firmado em caráter irrevogável e irretratável, para todos os fins de direitos e efeitos legais.
                                <br>
                                <b>5.6</b> O presente termo passa a vigorar entre as partes a partir do aceite expresso pelo CLIENTE OCULTO, as quais elegem o foro da cidade de Natal/RN para dirimirem quaisquer dúvidas provenientes do referido instrumento.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-filter').click(function(e){
                e.preventDefault();
                $('.modal-filter').modal();
            });
        });
    </script>
@endsection