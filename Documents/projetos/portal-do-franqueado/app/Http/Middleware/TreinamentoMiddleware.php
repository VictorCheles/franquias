<?php

namespace App\Http\Middleware;

use Closure;

class TreinamentoMiddleware
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
        if (! $user->isAdmin()) {
            if (str_is('*videos*', $request->url()) or str_is('*video*', $request->url())) {
            } else {
                $videos = $user->videosNaoAssistidos();
                if ($videos->count() > 0) {
                    session()->flash('warning', 'Existem ' . $videos->count() . ' vídeos não assistidos, <a href="'. url('/videos/dashboard') .'">clique aqui para ir para o Canal do Franquiado</a>');
                }
            }
        }

        return $next($request);
    }
}
