<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AcessoAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth()->user();
        if ($user->nivel_acesso == User::ACESSO_ADMIN) {
            return $next($request);
        }

        $back = '/backend';
        if (str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', \Route::current()->domain())) {
            $back = '/';
        }

        return redirect($back)->withErrors('Você não tem permissão para acessar este recurso');
    }
}
