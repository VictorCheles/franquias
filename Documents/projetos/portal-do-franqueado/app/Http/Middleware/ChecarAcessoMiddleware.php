<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ChecarAcessoMiddleware
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
        $url = '/backend/login';
        if (str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', $request->getHost())) {
            $url = '/login';
        }

        $user = Auth()->user();
        if ($user->status == User::STATUS_BLOQUEADO) {
            Auth()->logout();

            return redirect($url)->withErrors('Usuário bloqueado');
        }
        if ($user->status == User::STATUS_SOLICITADO) {
            Auth()->logout();

            return redirect($url)->withErrors('Seu cadastro ainda está pendente de análise');
        }

        return $next($request);
    }
}
