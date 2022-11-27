<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function token()
    {
        return response()->json([
            'token' => csrf_token(),
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth()->user();

        if ($user->lojas->count() > 0) {
            return view('backend.index');
        } else {
            $request->session()->flash('error', 'Você não pode validar cupons antes de ser vinculado á alguma franquia');
            if ($user->nivel_acesso == User::ACESSO_ADMIN) {
                $request->session()->flash('warning', link_to('/backend/usuarios/editar/' . $user->id, 'Vincular a uma franquia', ['class' => 'btn btn-info']));
            }

            return view('backend.index-error', compact('user'));
        }
    }
}
