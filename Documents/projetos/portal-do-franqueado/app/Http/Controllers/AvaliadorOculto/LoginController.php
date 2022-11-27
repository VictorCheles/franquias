<?php

namespace App\Http\Controllers\AvaliadorOculto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';
    protected $guard = 'avaliador_oculto';
    protected $loginView = 'avaliador-oculto.auth.login';

    public function showLoginForm(Request $request, $email = null)
    {
        if (\Auth::guard('avaliador_oculto')->check()) {
            return redirect()->route('dashboard');
        }

        return view($this->loginView, compact('email'));
    }
}
