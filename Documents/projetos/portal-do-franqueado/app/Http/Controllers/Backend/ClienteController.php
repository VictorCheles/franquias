<?php

namespace App\Http\Controllers\Backend;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
{
    public function listar(Request $request)
    {
        $tituloPagina = 'Clientes';
        $subTituloPagina = '';
        $lista = Cliente::where(function ($q) use ($request) {
            if ($request->input('filter.nome')) {
                $q->where('nome', 'like', "%{$request->input('filter.nome')}%");
            }
            if ($request->input('filter.email')) {
                $q->where('email', 'like', "%{$request->input('filter.email')}%");
            }
        })->orderBy('updated_at', 'desc')->paginate(10);

        return view('backend.clientes.listar', compact('tituloPagina', 'subTituloPagina', 'lista'));
    }

    public function listarExcel(Request $request)
    {
        if ($request->get('password') == env('MAILLING_PASSWORD')) {
            $clientes = \App\Models\Cliente::with(['municipio', 'municipio.estado'])->orderBy('nome')->get()->map(function (\App\Models\Cliente $cliente) {
                return [
                    'Nome' => $cliente->nome,
                    'Email' => mb_strtolower($cliente->email),
                    'Cidade' => $cliente->municipio ? $cliente->municipio->nome . ' - ' . $cliente->municipio->estado->sigla : '',
                ];
            })->toArray();

            \Excel::create('Clientes ' . env('APP_NAME'), function ($excel) use ($clientes) {
                $excel->sheet('Excel sheet', function ($sheet) use ($clientes) {
                    $sheet->protect('apenasadminfoda', function (\PHPExcel_Worksheet_Protection $protection) {
                        $protection->setSort(true);
                    });

                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($clientes);
                });
            })->export('xls');
        } else {
            return redirect()->back()->withErrors('Acesso negado');
        }
    }
}
