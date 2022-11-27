<?php

namespace App\Http\Middleware;

use Closure;

class BloqueioPedidoMiddleware
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
        if ($request->get('loja_id')) {
            $loja = $request->user()->lojas->filter(function ($loja) use ($request) {
                return $loja->id == $request->get('loja_id') and $loja->fazer_pedido;
            });

            if ($loja->count() > 0) {
                return $next($request);
            } else {
                return redirect()->to('/')->with('pedido_bloqueado', 'Recurso bloqueado, entre em contato com o ⁠⁠⁠setor financeiro');
            }
        } else {
            return $next($request);
        }
    }
}
