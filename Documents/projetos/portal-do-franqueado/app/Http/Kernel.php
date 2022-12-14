<?php

namespace App\Http;

use App\Http\Middleware\CheckTrialMode;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\CheckTrialMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\PersonificacaoMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'acesso_admin' => \App\Http\Middleware\AcessoAdminMiddleware::class,
        'treinamento' => \App\Http\Middleware\TreinamentoMiddleware::class,
        'checar_acesso' => \App\Http\Middleware\ChecarAcessoMiddleware::class,
        'avisos_do_programador' => \App\Http\Middleware\AvisosDoProgramadorMiddleware::class,
        'acl' => \App\Http\Middleware\ACLMiddleware::class,
        'bloqueio_pedido' => \App\Http\Middleware\BloqueioPedidoMiddleware::class,
        'avaliador_oculto_aceite' => \App\Http\Middleware\AvaliadorOcultoAceiteMiddleware::class,
        'aceite' => \App\Http\Middleware\AceiteMiddleware::class,
    ];
}
