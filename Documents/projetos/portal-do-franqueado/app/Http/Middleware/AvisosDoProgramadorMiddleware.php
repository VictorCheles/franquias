<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AvisosDoProgramadorMiddleware
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
//        if($request->user()->isAdmin())
//        {
//            $count = User::whereNull('grupo_id')->count();
//            if($count > 0)
//            {
//                $aviso = "
//                Existem {$count} usuários sem grupo de permissões<br>
//                <a href=\"" . route('admin.grupos.create') . "\">Clique aqui para cadastra grupos</a><br>
//                <a href=\"" . url('backend/usuarios/listar') . "\">Clique aqui para vincular usuários a um grupo</a><br>
//                ";
//                $request->session()->flash('aviso_do_programador', $aviso);
//            }
//        }
        return $next($request);
    }
}
