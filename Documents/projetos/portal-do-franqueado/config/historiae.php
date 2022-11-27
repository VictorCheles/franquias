<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models with change logs
    |--------------------------------------------------------------------------
    |
    | Here you may specify which models you want your application to log changes.
    |
    */

    'models' => [
        \App\User::class,
        \App\Models\Arquivo::class,
        \App\Models\ArquivoUsuario::class,
        \App\Models\Banco::class,
        \App\Models\CategoriaProduto::class,
        \App\Models\Cliente::class,
        \App\Models\ClienteLoja::class,
        \App\Models\ClienteLojaEstabelecimento::class,
        \App\Models\Comunicado::class,
        \App\Models\ComunicadoDestinatarios::class,
        \App\Models\Cupom::class,
        \App\Models\Enquete::class,
        \App\Models\EventoCalendario::class,
        \App\Models\Imagem::class,
        \App\Models\Loja::class,
        \App\Models\Notificacao::class,
        \App\Models\Pasta::class,
        \App\Models\Pedido::class,
        \App\Models\Pergunta::class,
        \App\Models\Personificacao::class,
        \App\Models\Praca::class,
        \App\Models\Produto::class,
        \App\Models\Promocao::class,
        \App\Models\Resposta::class,
        \App\Models\Setor::class,
        \App\Models\SocialAccount::class,
        \App\Models\Solicitacao::class,
        \App\Models\SolicitacaoHistorico::class,
        \App\Models\Tag::class,
        \App\Models\UsuarioResposta::class,
        \App\Models\Video::class,
        \App\Models\VideoAssisido::class,
        \App\Models\Vitrine::class,
        \App\Models\AvaliadorOculto\Formulario::class,
        \App\Models\AvaliadorOculto\Pergunta::class,
        \App\Models\AvaliadorOculto\Resposta::class,
        \App\Models\AvaliadorOculto\RespostaSubjetiva::class,
        \App\Models\AvaliadorOculto\User::class,
        \App\Models\Comunicado\Questionamento::class,
        \App\Models\ConsultoriaCampo\Resposta::class,
        \App\Models\ConsultoriaCampo\Visita::class,
        \App\Models\ConsultoriaCampo\Pergunta::class,
        \App\Models\ConsultoriaCampo\Formulario::class,
        \App\Models\ConsultoriaCampo\AcaoCorretiva::class,
        \App\Models\ConsultoriaCampo\Notificacao::class,
        \App\Models\ConsultoriaCampo\Topico::class,
        \App\Models\Metas\Meta::class,
        \App\Models\Metas\Atividade::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Verbose names for models
    |--------------------------------------------------------------------------
    |
    | Here you may specify verbose names for your logged models.
    |
    */

    'labels' => [],

    /*
    |--------------------------------------------------------------------------
    | Access log
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want your application to log users' accesses.
    |
    */

    'access' => false,

    /*
    |--------------------------------------------------------------------------
    | Middleware groups
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want your routes to use specific middleware groups.
    |
    */

    'middleware' => ['web', 'auth'],

];
