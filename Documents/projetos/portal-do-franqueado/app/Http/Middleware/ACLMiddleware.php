<?php

namespace App\Http\Middleware;

use Closure;

class ACLMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
//        var_dump('passei por aqui e sai correndo', $roles);
        $continue = $request->user()->hasAnyRole($roles);

        if ($continue) {
            return $next($request);
        } else {
            return redirect('/')->withErrors('Página não acessível');
        }
    }
}
