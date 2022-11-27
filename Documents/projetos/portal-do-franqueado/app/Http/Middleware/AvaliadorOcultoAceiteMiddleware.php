<?php

namespace App\Http\Middleware;

use Closure;

class AvaliadorOcultoAceiteMiddleware
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
        if ($request->user('avaliador_oculto')->aceite != 1) {
            return redirect('/');
        }

        return $next($request);
    }
}
