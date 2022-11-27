<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$full_url = url()->current();
if (str_contains($full_url, 'www')) {
    $domain_cupons = 'www.' . env('APP_DOMAIN');
    $domain_franqueado = 'www.' . str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_PORTAL_FRANQUEADO'), env('APP_DOMAIN')]));
    $domain_oculto = 'www.' . str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_AVALIADOR_OCULTO'), env('APP_DOMAIN')]));
} else {
    $domain_cupons = env('APP_DOMAIN');
    $domain_franqueado = str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_PORTAL_FRANQUEADO'), env('APP_DOMAIN')]));
    $domain_oculto = str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_AVALIADOR_OCULTO'), env('APP_DOMAIN')]));
}

Route::group(['domain' => $domain_cupons], function () {

    Route::get('exibir-voucher/{voucher}', function ($voucher) {
        return response()->download('uploads/cliente_oculto_comprovantes/' . $voucher);
    });

    /*
     * Social default redirect
     */
    Route::get('redirect/{provider}/{promocao_id}', 'Auth\SocialController@redirect')->name('social.redirect');

    /*
     * Facebook Routes
     */
    Route::get('social/callback/facebook', 'Auth\SocialController@callbackFacebook')->name('social.callback.facebook');

    Route::get('/', 'HomeController@index');
    Route::get('/promocao/{id}', 'HomeController@promocao');

    Route::get('/promocao/{id}/cupom', 'HomeController@formCliente');
    Route::post('/promocao/{id}/cupom', 'HomeController@cupom');
    Route::get('/promocao/{id}/cupom/{cupom}', 'HomeController@mostrarCupom');

    Route::get('/termos-e-condicoes', 'HomeController@termosCondicoes');
    Route::get('/lojasparticipantes', 'HomeController@lojasParticipantes');
    Route::get('/login', function () {
        return redirect('/backend/login');
    });

    Route::group(['prefix' => 'backend'], function () {
        Route::auth();
        Route::group(['middleware' => ['auth', 'checar_acesso', 'avisos_do_programador']], function () {
            Route::get('/', 'BackendController@index');
            Route::get('novo_csrf', 'BackendController@token');

            Route::group(['namespace' => 'Backend'], function () {
                Route::post('/cupons/validar', 'CupomController@validarCupom');

                Route::group(['prefix' => 'bi'], function () {
                    Route::get('cupons-por-dia-caixa', 'BIController@cuponsPorDiaCaixa');
                });
            });

            Route::group(['middleware' => 'acesso_admin'], function () {
                Route::group(['namespace' => 'Backend'], function () {
                    Route::group(['prefix' => 'promocoes'], function () {
                        Route::get('/listar', 'PromocaoController@listar');

                        Route::get('/criar', 'PromocaoController@criar');
                        Route::post('/criar', 'PromocaoController@processarCriar');

                        Route::get('/editar/{id}', 'PromocaoController@editar');
                        Route::post('/editar/{id}', 'PromocaoController@processarEditar');

                        Route::post('/excluir', 'PromocaoController@processarExcluir');
                    });

                    Route::group(['prefix' => 'bi'], function () {
                        Route::get('/dashboard', 'BIController@dashboard');
                        Route::get('/promocoes', 'BIController@promocoes');
                        Route::get('/cupons-por-dia', 'BIController@cuponsPorDia');
                    });

                    Route::group(['prefix' => 'clientes'], function () {
                        Route::get('/listar', 'ClienteController@listar');
                        Route::get('/listar/excel', 'ClienteController@listarExcel');
                    });

                    Route::group(['prefix' => 'cupons'], function () {
                        Route::get('/buscar', 'CupomController@buscarCupom');
                    });
                });
            });
        });
    });
});

Route::group(['domain' => $domain_franqueado], function () {
    Route::auth();

    Route::get('aceite', 'AceiteController@index')->name('aceite');
    Route::post('aceite', 'AceiteController@post')->name('aceite');

    Route::group(['middleware' => ['auth', 'treinamento', 'checar_acesso', 'avisos_do_programador', 'aceite']], function () {

        /*
         * coisas q já foram feitas nos cupons
         */
        Route::get('backend/usuarios/despersonificar', 'Backend\UserController@despersonificacao');
        Route::group(['namespace' => 'Backend', 'prefix' => 'backend', 'middleware' => 'acesso_admin'], function () {
            Route::get('clientes/listar/excel', 'ClienteController@listarExcel');

            Route::group(['prefix' => 'usuarios'], function () {
                Route::get('/listar', 'UserController@listar');

                Route::get('/criar', 'UserController@criar');
                Route::post('/criar', 'UserController@processarCriar');

                Route::get('/editar/{id}', 'UserController@editar');
                Route::post('/editar/{id}', 'UserController@processarEditar');

                Route::get('/meus-dados', 'UserController@meusDados');
                Route::post('/meus-dados', 'UserController@processarMeusDados');

                Route::get('/personificar/{personagem_id}', 'UserController@personificacao');
            });

            Route::resource('praca', 'PracaController');
            Route::group(['prefix' => 'franquias'], function () {
                Route::get('/listar', 'LojaController@listar');

                Route::get('/criar', 'LojaController@criar');
                Route::post('/criar', 'LojaController@processarCriar');

                Route::get('/editar/{id}', 'LojaController@editar');
                Route::post('/editar/{id}', 'LojaController@processarEditar');

                Route::get('/editar/{id}/fazerpedido', 'LojaController@fazerPedido');
            });
        });

        Route::group(['namespace' => 'Franquias'], function () {
            /*
             * ÁREA PÚBLICA DO FRANQUEADO
             */
            Route::get('/', 'DashboardController@index');
            Route::get('/dashboard/ajax/calendario', 'DashboardController@calendarioPequeno');
            Route::get('/calendario', 'DashboardController@calendario');
            Route::get('comunicados/ler/{id}', 'Admin\ComunicadoController@ler');
            Route::get('comunicados/listar', 'Admin\ComunicadoController@publicoListar');

            Route::post('questionamento/franqueado/criar', 'QuestionamentoController@createFranqueado')->name('questionamento.franqueado.create');
            Route::post('questionamento/admin/responder', 'QuestionamentoController@createAdmin')->name('questionamento.admin.reply');

            Route::resource('solicitacao', 'Admin\SolicitacaoController', ['except' => ['edit', 'destroy']]);
            Route::resource('video', 'Admin\VideoController', ['only' => ['show']]);
            Route::get('videos/dashboard', 'Admin\VideoController@dashboard');
            Route::put('ajaxVerVideo', 'Admin\VideoController@ajaxVerVideo');

            Route::get('fornecimento/datalimite', 'Admin\FornecimentoController@atualizarDataPedidoGet')->name('admin.fornecimento.datapedido.index');
            Route::post('fornecimento/datalimite', 'Admin\FornecimentoController@atualizarDataPedidoPost')->name('admin.fornecimento.datapedido.update');

            Route::get('fornecimento/pedidominimo', 'Admin\FornecimentoController@atualizarPedidoMinimoGet')->name('admin.fornecimento.pedidominimo.index');
            Route::post('fornecimento/pedidominimo', 'Admin\FornecimentoController@atualizarPedidoMinimoPost')->name('admin.fornecimento.pedidominimo.update');

            Route::resource('pedido', 'Admin\PedidoController');
            Route::get('pedido/{id}/tornarRecebido', 'Admin\PedidoController@turnPedidoRecebido')->name('pedido.receber');
            Route::get('pedido/{id}/imprimir', 'Admin\PedidoController@imprimirPedido')->name('pedido.imprimir');
            Route::get('pedido/{id}/extrato_excel', 'Admin\PedidoController@extratoExcel')->name('pedido.extrato.excel');

            Route::get('pedido/{id}/verificacao', 'Admin\PedidoController@verificarPedidoGet')->name('pedido.verificacao');
            Route::post('pedido/{id}/verificacao', 'Admin\PedidoController@verificarPedidoPost')->name('pedido.verificacao');

            Route::get('arquivo/{id}/download', 'Admin\ArquivoController@download')->name('arquivo.download');
            Route::get('arquivo/{id}/setor', 'Admin\ArquivoController@index')->name('arquivo.setor');
            Route::get('enquetes/{id}/responder', 'Admin\EnqueteController@responder')->name('enquetes.responder');
            Route::put('enquetes/{id}/responder', 'Admin\EnqueteController@processarResposta')->name('enquetes.processar.resposta');
            Route::get('enquetes', 'Admin\EnqueteController@indexPublic')->name('enquetes.index');
            Route::get('busca', 'DashboardController@busca');

            Route::resource('clientes_loja', 'Admin\ClienteLojaController');
            Route::get('clientes_loja_exportar_excel', 'Admin\ClienteLojaController@exportarExcel')->name('clientes_loja.exportar.excel');
            Route::resource('clientes_loja_estabelecimento', 'Admin\ClienteLojaEstabelecimentoController');
            /*
             *
             */
            Route::get('limpar-notificacoes/{tipo}', function ($tipo) {
                $all = \App\Models\Notificacao::whereTipo($tipo)
                    ->whereDestinatario(Auth()->user()->id)
                    ->whereStatus(false);
                if ($all->count() > 0) {
                    $all->update(['status' => true]);
                }

                return redirect()->back();
            })->name('limpar.notificacoes');

            Route::group(['prefix' => 'modulo-de-metas'], function () {
                Route::get('/', 'Admin\Metas\MetaController@index')->name('modulo-de-metas');
                Route::get('/metas/{id}', 'Admin\Metas\MetaController@show')->name('admin.modulo-de-metas.metas.show');
            });

            Route::group(['prefix' => 'mensagens'], function () {
                Route::get('/', 'Admin\MensagemController@index')->name('admin.mensagens');
                Route::get('mensagem/{id?}', 'Admin\MensagemController@create')->name('admin.mensagens.create');
                Route::post('mensagem', 'Admin\MensagemController@store')->name('admin.mensagens.store');
                Route::get('mensagem/{id}/ver', 'Admin\MensagemController@show')->name('admin.mensagens.show');
                Route::get('enviadas', 'Admin\MensagemController@enviadas')->name('admin.mensagens.enviadas');
            });

            Route::group(['middleware' => 'acesso_admin'], function () {

                /*
                 * Historiae
                 */
                Route::group([
                    'prefix' => 'logs',
                ], function () {
                    Route::get('accesses', '\App\Http\Controllers\AccessLogsController@index');
                    Route::get('changes', '\App\Http\Controllers\ChangeLogsController@index');
                });
                Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


                Route::group(['namespace' => 'Admin'], function () {
                    Route::group(['prefix' => 'admin'], function () {
                        Route::group(['prefix' => 'comunicados'], function () {
                            Route::get('/listar', 'ComunicadoController@listar');
                            Route::get('/criar', 'ComunicadoController@criar');
                            Route::post('/criar', 'ComunicadoController@processarCriar');
                            Route::get('/editar/{id}', 'ComunicadoController@editar');
                            Route::post('/editar/{id}', 'ComunicadoController@processarEditar');
                            Route::delete('/deletar/{id}', 'ComunicadoController@processarDeletar');
                            Route::delete('/excluir/imagem', 'ComunicadoController@deletarImagem')->name('admin.comunicado.excluir.imagem');
                            Route::put('/encerrar/assunto/{comunicado_id}', 'ComunicadoController@abrirEncerrarAssunto')->name('admin.comunicado.encerrar.assunto');
                        });

                        Route::resource('vitrine', 'VitrineController');
                        Route::resource('setor', 'SetorController');

                        Route::delete('setor/{id}/remanejamento/destroy', 'SetorController@destroyRemanejamento')->name('admin.setor.destroy.remanejamento');
                        Route::delete('setor/destroy/{id}/cascade', 'SetorController@destroyCascade')->name('admin.setor.destroy.cascade');

                        Route::resource('solicitacao', 'SolicitacaoController');
                        Route::resource('tag', 'TagController');

                        Route::delete('tag/{id}/remanejamento/destroy', 'TagController@destroyRemanejamento')->name('admin.tag.destroy.remanejamento');
                        Route::delete('tag/destroy/{id}/cascade', 'TagController@destroyCascade')->name('admin.tag.destroy.cascade');

                        Route::resource('video', 'VideoController');
                        Route::resource('produto', 'ProdutoController', ['except' => ['destroy']]);
                        Route::get('ajax/produtos', 'ProdutoController@ajaxList')->name('admin.ajax.produtos');
                        Route::resource('pedido', 'PedidoController', ['only' => ['edit', 'update', 'destroy']]);
                        Route::get('pedidos/abertos', 'PedidoController@pedidosAbertos')->name('admin.pedidos.abertos');
                        Route::resource('categoria', 'CategoriaProdutoController');
                        Route::resource('arquivo', 'ArquivoController');
                        Route::resource('pasta', 'PastaController');

                        Route::delete('pasta/{id}/remanejamento/destroy', 'PastaController@destroyRemanejamento')->name('admin.pasta.destroy.remanejamento');
                        Route::delete('pasta/destroy/{id}/cascade', 'PastaController@destroyCascade')->name('admin.pasta.destroy.cascade');

                        Route::get('arquivo/lista/admin', 'ArquivoController@indexAdmin')->name('admin.arquivo.index.admin');

                        Route::resource('enquetes', 'EnqueteController');
                        Route::resource('grupos', 'GrupoController');

                        Route::resource('imagem', 'ImagemController');

                        Route::group(['prefix' => 'consultoria-de-campo'], function () {
                            Route::get('/', 'ConsultoriaCampo\SetupController@index')->name('admin.consultoria-de-campo');
                            Route::get('setup', 'ConsultoriaCampo\SetupController@setup')->name('admin.consultoria-de-campo.setup');
                            Route::resource('setup/formularios', 'ConsultoriaCampo\FormularioController');
                            Route::resource('setup/notificacoes', 'ConsultoriaCampo\NotificacaoController');
                            Route::resource('visitas', 'ConsultoriaCampo\VisitaController');

                            Route::delete('perguntas/{id}/destroy', 'ConsultoriaCampo\FormularioController@removerPerguntaAjax')->name('admin.consultoria-de-campo.setup.perguntas.destroy');
                            Route::delete('topicos/{id}/destroy', 'ConsultoriaCampo\FormularioController@removerTopicoAjax')->name('admin.consultoria-de-campo.setup.topicos.destroy');

                            Route::delete('visitas/{visita_id}/notificacao/{notificacao_id}/destroy', 'ConsultoriaCampo\VisitaController@destroyNotificacao')->name('admin.programa-de-qualidade.visitas.notificacao.destroy');
                            Route::put('visitas/{visita_id}/notificacao/create', 'ConsultoriaCampo\VisitaController@createNotificacao')->name('admin.programa-de-qualidade.visitas.notificacao.create');
                            Route::get('visitas/{id}/detalhes', 'ConsultoriaCampo\VisitaController@detalhes')->name('admin.consultoria-de-campo.visitas.detalhes');

                            /*
                             * Preguiça? sim
                             */
                            Route::get('visita_preview', function () {
                                $item = \App\Models\ConsultoriaCampo\Visita::findOrFail(Request::get('form_id_to_preview'));

                                return view('portal-franqueado.admin.consultoria-campo.visitas.relatorios.preview', [
                                    'item' => $item,
                                    'request' => Request::all(),
                                ]);
                            })->name('admin.consultoria-de-campo.visitas.preview');

                            Route::resource('acoes-corretivas', 'ConsultoriaCampo\AcaoCorretivaController');
                        });

                        Route::group(['prefix' => 'modulo-de-metas'], function () {
                            Route::resource('metas', 'Metas\MetaController', ['except' => ['show']]);
                            Route::post('meta/{id}/atividades', 'Metas\AtividadeController@store')->name('admin.modulo-de-metas.atividades.store');
                            Route::post('atividades/{id}/update', 'Metas\AtividadeController@update')->name('admin.modulo-de-metas.atividades.update');
                            Route::delete('atividades/{id}/destroy', 'Metas\AtividadeController@destroy')->name('admin.modulo-de-metas.atividades.destroy');
                        });
                    });
                });
            });
        });

        Route::get('avaliadoroculto/dashboard', 'AvaliadorOculto\DashboardController@adminDashboard')->name('avaliadoroculto.dashboard');
        Route::resource('avaliadoroculto/users', 'AvaliadorOculto\UserController');
        Route::get('avaliadoroculto/users/{id}/formularios', 'AvaliadorOculto\UserController@formularios')->name('avaliadoroculto.users.formularios');
        Route::post('avaliadoroculto/users/{id}/formularios', 'AvaliadorOculto\UserController@formulariosPost')->name('avaliadoroculto.users.formularios.post');
        Route::get('avaliadoroculto/users/{id}/email-chamada', 'AvaliadorOculto\UserController@emailChamada')->name('avaliadoroculto.users.email.chamada');

        Route::resource('avaliadoroculto/formularios', 'AvaliadorOculto\FormularioController');
        Route::get('avaliadoroculto/ranking', 'AvaliadorOculto\FormularioController@ranking')->name('avaliadoroculto.ranking');

        Route::patch('avaliadoroculto/formularios{id}/updatepeso', 'AvaliadorOculto\FormularioController@updatePeso')->name('avaliadoroculto.formularios.updatepeso');

        Route::get('avaliadoroculto/formularios/{id}/toggleActive', 'AvaliadorOculto\FormularioController@toggleActive')->name('avaliadoroculto.formularios.toggle.active');
        Route::get('avaliadoroculto/formularios/{id}/estatisticas', 'AvaliadorOculto\FormularioController@estatisticas')->name('avaliadoroculto.formularios.estatisticas');
        Route::delete('avaliadoroculto/formularios/resetar/formulario', 'AvaliadorOculto\FormularioController@resetarFormulario')->name('avaliadoroculto.formularios.resetar.formulario');
        Route::delete('avaliadoroculto/formularios/remover/formulario', 'AvaliadorOculto\FormularioController@removerFormulario')->name('avaliadoroculto.formularios.remover.formulario');

        Route::get('ajax_avaliadoroculto/formularios_da_loja/{id}', 'AvaliadorOculto\FormularioController@ajaxObterListaFormulariosLoja')->name('ajax.avaliadoroculto.formulario.loja');

        Route::post('ajax_avaliadoroculto/formularios/nova_pergunta', 'AvaliadorOculto\FormularioController@ajaxAdicionarPergunta')->name('ajax.avaliadoroculto.formularios.nova.pergunta');
        Route::post('ajax_avaliadoroculto/formularios/remover_pergunta', 'AvaliadorOculto\FormularioController@ajaxRemoverPergunta')->name('ajax.avaliadoroculto.formularios.remover.pergunta');
    });
});

Route::group(['domain' => $domain_oculto], function () {
    Route::get('/login/{email?}', 'AvaliadorOculto\LoginController@showLoginForm')->name('login');
    Route::post('/login/{email?}', 'AvaliadorOculto\LoginController@postLogin')->name('login.post');
    Route::get('/logout', 'AvaliadorOculto\LoginController@getLogout')->name('logout');
    Route::get('/auto-cadastro/{token?}', 'AvaliadorOculto\AutoCadastroController@getAutoCadastro')->name('auto.cadastro');
    Route::post('/auto-cadastro/{token?}', 'AvaliadorOculto\AutoCadastroController@postAutoCadastro')->name('auto.cadastro.post');
    Route::get('/cadastro-finalizado', 'AvaliadorOculto\AutoCadastroController@getCadastroFinalizado')->name('cadastro.finalizado');

    Route::group(['middleware' => ['auth:avaliador_oculto']], function () {
        Route::get('/', 'AvaliadorOculto\DashboardController@index');
        Route::post('/', 'AvaliadorOculto\DashboardController@postIndex')->name('postIndex');

        Route::group(['middleware' => ['avaliador_oculto_aceite']], function () {
            Route::get('dashboard', 'AvaliadorOculto\DashboardController@dashboard')->name('dashboard');
            Route::post('responder-formulario', 'AvaliadorOculto\FormularioController@responder')->name('responder.formulario');

            Route::get('enviar_comprovante/{formulario_id}/{loja_id}', 'AvaliadorOculto\FormularioController@enviarComprovante')->name('enviar.comprovante');
            Route::post('enviar_comprovante/{formulario_id}/{loja_id}', 'AvaliadorOculto\FormularioController@enviarComprovantePost')->name('enviar.comprovante.post');
        });
    });
});
