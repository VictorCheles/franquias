<?php

namespace App\Http\Middleware;

use Closure;

class AceiteMiddleware
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
        if ($request->user()->aceite) {
            return $next($request);
        } else {
            return redirect()->route('aceite');
        }
    }
}
