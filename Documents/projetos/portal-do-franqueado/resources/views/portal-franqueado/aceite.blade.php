@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Termo de aceite de uso do Portal do Franqueado
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                {!! Form::open(['url' => route('aceite')]) !!}
                    <div class="box-header with-border">
                        <i class="fa fa-check-square-o"></i>

                        <h3 class="box-title">Termo</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ol class="text-justify">
                            <li>
                                Você esta acessando o Portal do franqueado, através do seu login e
                                senha individual pela primeira vez. Esse login é pessoal e
                                intransferivel, ao aceitar este termo o franqueado(loja) concorda e se
                                responsabiliza por todas as informações e o sigilo contido no nosso
                                portal
                            </li>
                            <li>
                                O portal do franqueado é nossa principal ferramenta de comunicação
                                e como tal, as informações contidas são de vital importancia para o
                                andamento das operações.
                            </li>
                            <li>
                                O Franqueado deverá manter durante toda a vigência deste contrato,
                                sigilo e confidencialidade, a respeito de qualquer informações
                                técnicas ou não que estejam inseridas no portal do franqueado. Por
                                ocasião da contratação de cada um de seus profissionais ou de
                                prestadores de serviço que tenham acesso às informações, exigindo
                                que os mesmos assinem um Termo de Sigilo, conforme padrão
                                incluído no Manual de Operações da <b>{{ env('APP_NAME') }}</b>.
                            </li>
                            <li>
                                O Franqueado(loja) deverá Ter participação sinérgica e ativa em
                                tudo o que diz respeito à filosofia, políticas, diretrizes, normas de
                                procedimento, planos de ação, etc, inerentes ao Sistema de
                                Franquias <b>{{ env('APP_NAME') }}</b> enviadas através do nosso portal e todas as
                                informações enviadas e não contestadas serão entendidas como
                                aceitas.
                            </li>
                            <li>
                                Todas as recomendações formuladas pela Franqueadora serão
                                sempre feitas através do portal do franqueado e deverão ser
                                acatadas pelo Franqueado, visando a manutenção dos padrões de
                                qualidade e operacional da Rede, definidos no MANUAL de Operação
                                e a manutenção do SISTEMA.
                            </li>
                            <li>
                                O Franqueado(loja) se obriga, desde logo, a não praticar, nem
                                permitir que se pratique, qualquer ato ou omissão que prejudique, ou
                                possa vir a prejudicar, a imagem da Franqueadora e/ou de qualquer
                                Marca Franqueada ou bem imaterial que compõe o Sistema
                                Franqueado do nosso portal.
                            </li>
                            <li>
                                O Franqueado(loja) desde logo se obriga a cumprir à risca e a fazer
                                com que sua equipe ou prepostos também cumpram todas as
                                determinações, diretrizes e especificações contidas no portal ou em
                                suas subsequentes alterações ou desdobramentos e também em
                                quaisquer outros materiais que venham a ser criados ou adotados
                                pela Franqueadora.
                            </li>
                        </ol>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="aceite" value="1">
                                    <b>Eu aceito e concordo com os temos de uso</b>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! link_to('logout', 'Sair do portal', ['class' => 'btn btn-flat btn-danger']) !!}
                        {!! Form::submit('Continuar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection