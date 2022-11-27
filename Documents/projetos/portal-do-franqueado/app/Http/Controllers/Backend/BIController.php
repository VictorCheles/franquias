<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Loja;
use App\Models\Cupom;
use App\Models\Promocao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BIController extends Controller
{
    public function dashboard(Request $request)
    {
        $opcoesVisualizacoes = ['bar' => 'Barras', 'column' => 'Coluna', 'pie' => 'Pizza'];
        $tituloAba = 'Dados da semana | Backend | ' . env('APP_NAME');
        $subTituloPagina = 'Dados da semana';
        $inicio_semana = Carbon::now()->startOfWeek();
        $fim_semana = Carbon::now()->endOfWeek();

        if ($request->input('filter')) {
            if ($request->input('filter.inicio')) {
                $inicio_semana = Carbon::parse($request->input('filter.inicio'));
            }
            if ($request->input('filter.fim')) {
                $fim_semana = Carbon::parse($request->input('filter.fim'));
            }
        }

        $promocoes = Promocao::ativas();
        $cupons = Cupom::porSemanaPorStatus($inicio_semana, $fim_semana);
        $franquias = Loja::cuponsPorSemanaPorLoja($inicio_semana, $fim_semana);

        return view('backend.bi.dashboard', compact('tituloAba', 'subTituloPagina', 'inicio_semana', 'fim_semana', 'promocoes', 'cupons', 'franquias', 'opcoesVisualizacoes'));
    }

    public function promocoes(Request $request)
    {
        $promocoesFilter = collect();
        if ($request->input('filter.promocoes')) {
            foreach ($request->input('filter.promocoes') as $p) {
                if (! empty($p)) {
                    $promocoesFilter->push($p);
                }
            }
        }
        $promocoes = Promocao::promocoesComMaisCupons($request, $promocoesFilter);
        $selectPromocoes = Promocao::orderBy('created_at', 'desc')->lists('nome', 'id');

        return view('backend.bi.promocoes', compact('promocoes', 'selectPromocoes'));
    }

    public function cuponsPorDia(Request $request)
    {
        $forceToday = false;
        if (! $request->input('filter.data')) {
            $forceToday = true;
        }
        $lista = Cupom::cuponsPorDiaPorLoja($request, $forceToday);

        $tituloAba = $tituloPagina = 'BI Cupons por dia';
        $subTituloPagina = '';

        return view('backend.bi.cupons-por-dia', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'lista'));
    }

    public function cuponsPorDiaCaixa(Request $request)
    {
        $forceToday = false;
        if (! $request->input('filter.data')) {
            $forceToday = true;
        }
        $lista = Cupom::cuponsPorDiaCaixa($request, $forceToday);
        $franquias = Auth()->user()->lojas;
        $tituloAba = $tituloPagina = 'BI Cupons por dia';
        $subTituloPagina = 'usados nesta Franqua';

        return view('backend.bi.cupons-por-dia-caixa', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'lista', 'franquias'));
    }
}
