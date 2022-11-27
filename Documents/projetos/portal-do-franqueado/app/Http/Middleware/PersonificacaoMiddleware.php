<?php

namespace App\Http\Middleware;

use Closure;

class PersonificacaoMiddleware
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
        if (\Session::has('personificacao')) {
            \Session::flash('flash_personificacao', 'Modo personificação <b>ativo</b>, você está atuando como [' . \Session::get('personificacao')->personagem->nome . '] <br><a href="' . url('backend/usuarios/despersonificar') . '">Volte a ser você [' . \Session::get('personificacao')->ator->primeiro_nome . ']</a>');
        }

        return $next($request);
    }
}
