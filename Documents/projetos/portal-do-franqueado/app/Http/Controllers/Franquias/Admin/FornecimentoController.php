<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\Praca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FornecimentoController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.fornecimento.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_PRACAS_EDITAR)->only(['atualizarDataPedidoGet', 'atualizarDataPedidoPost']);
        $this->middleware('acl:' . Recurso::ADM_LOJAS_EDITAR)->only(['atualizarPedidoMinimoGet', 'atualizarPedidoMinimoPost']);
    }

    public function atualizarDataPedidoGet()
    {
        $lista = Praca::orderBy('nome')->get();

        return view(self::VIEWS_PATH . 'data-pedido', compact('lista'));
    }

    public function atualizarDataPedidoPost(Request $request)
    {
        $this->validate($request, ['data_limite_pedido' => 'array']);

        DB::beginTransaction();

        try {
            foreach ($request->get('data_limite_pedido') as $praca_id => $data_limite) {
                $praca = Praca::findOrFail($praca_id);
                $data_orig = $praca->data_limite_pedido;
                $praca->data_limite_pedido = $data_limite;
                $praca->save();
                if (! Carbon::parse($data_limite)->eq(Carbon::parse($data_orig))) {
                    $praca->criarEventoCalendario();
                }
            }

            DB::commit();

            return redirect()->route('admin.fornecimento.datapedido.index')->with('success', 'Data modificadas com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.fornecimento.datapedido.index')->withErrors($ex->getMessage());
        }
    }

    public function atualizarPedidoMinimoGet()
    {
        $lista = Loja::orderBy('nome')->get();

        return view(self::VIEWS_PATH . 'pedido-minimo', compact('lista'));
    }

    public function atualizarPedidoMinimoPost(Request $request)
    {
        $this->validate($request, ['valor_minimo_pedido' => 'array']);

        DB::beginTransaction();

        try {
            foreach ($request->get('valor_minimo_pedido') as $loja_id => $valor) {
                $loja = Loja::findOrFail($loja_id);
                $loja->valor_minimo_pedido = unmaskMoney($valor);
                $loja->save();
            }

            DB::commit();

            return redirect()->route('admin.fornecimento.pedidominimo.index')->with('success', 'Valor do pedido mínimo modificado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.fornecimento.pedidominimo.index')->withErrors($ex->getMessage());
        }
    }
}
