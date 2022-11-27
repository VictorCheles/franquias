<?php

namespace App\Http\Controllers\Franquias\Admin\ConsultoriaCampo;

use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.consultoria-campo.';

    public function index()
    {
        return view(self::VIEWS_PATH . 'index');
    }

    public function setup()
    {
        return view(self::VIEWS_PATH . 'setup');
    }
}
