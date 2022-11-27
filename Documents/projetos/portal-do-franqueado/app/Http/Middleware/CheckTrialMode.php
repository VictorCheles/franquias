<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Session;

class CheckTrialMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $file_path = storage_path() . '/framework/trial';
        if (file_exists($file_path)) {
            $content = trim(file_get_contents($file_path));
            $hoje = Carbon::today();
            $trial = Carbon::parse($content);
            if ($trial->lte($hoje)) {
                throw new HttpException(402);
            }

            $diff = $hoje->diffInDays($trial);
            if ($diff <= 7) {
                Session::flash(
                    'trial',
                    'Você tem apenas ' . $diff . ' para utilizar o modo Trial do Sistema'
                );
            }
        }

        return $next($request);
    }
}
