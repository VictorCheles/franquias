<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AceiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checar_acesso');
    }

    public function index()
    {
        return view('portal-franqueado.aceite');
    }

    public function post(Request $request)
    {
        $this->validate($request, ['aceite' => 'required']);
        $u = Auth()->user();
        $u->aceite = true;
        $u->save();

        return redirect()->to('/');
    }
}
