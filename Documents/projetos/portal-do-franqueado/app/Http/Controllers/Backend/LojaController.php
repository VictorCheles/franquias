<?php

namespace App\Http\Controllers\Backend;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LojaController extends Controller
{
    public function __construct()
    {
        if (str_is('*' . env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', url()->current())) {
            $this->middleware('acl:' . Recurso::ADM_LOJAS_LISTAR)->only(['listar']);
            $this->middleware('acl:' . Recurso::ADM_LOJAS_CRIAR)->only(['criar']);
            $this->middleware('acl:' . Recurso::ADM_LOJAS_EDITAR)->only(['editar']);
        }
    }

    public function listar(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Nova Loja',
                'url' => url('/backend/franquias/criar'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_LOJAS_CRIAR,
            ],
        ]);

        $lista = Loja::where(function ($q) use ($request) {
            if ($request->input('filter.nome')) {
                $q->where('nome', 'like', "%{$request->input('filter.nome')}%");
            }
            if ($request->input('filter.uf')) {
                $q->where('uf', 'like', "{$request->input('filter.uf')}");
            }
            if ($request->input('filter.cidade')) {
                $q->where('cidade', 'like', "%{$request->input('filter.cidade')}%");
            }

            if ($fazer_pedido = $request->input('filter.fazer_pedido')) {
                if ($fazer_pedido == 1) {
                    $pode = true;
                } else {
                    $pode = false;
                }
                $q->whereFazerPedido($pode);
            }
        })->orderBy('nome', 'asc')->paginate(10);

        return view('backend.lojas.listar', compact('lista', 'quick_actions'));
    }

    public function processarCriar(Request $request)
    {
        $validator = Validator::make($request->all(), Loja::regrasValidacaoCriar());
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            Loja::create([
                'nome' => $request->get('nome'),
                'cep' => $request->get('cep'),
                'uf' => $request->get('uf'),
                'cidade' => $request->get('cidade'),
                'bairro' => $request->get('bairro'),
                'endereco' => $request->get('endereco'),
                'numero' => $request->get('numero'),
                'valor_minimo_pedido' => unmaskMoney($request->get('valor_minimo_pedido')),
                'data_limite_pedido' => $request->get('data_limite_pedido'),
                'praca_id' => $request->get('praca_id'),
                'complemento' => $request->get('complemento'),
            ]);
            DB::commit();

            return redirect('/backend/franquias/listar')->with('success', 'Franquia cadastrada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
        }
    }

    public function criar()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => url('/backend/franquias/listar'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_LOJAS_LISTAR,
            ],
        ]);

        return view('backend.lojas.criar', compact('quick_actions'));
    }

    public function processarEditar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Loja::regrasValidacaoCriar());
        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $item = Loja::findOrFail($id);
            $campos = Loja::regrasValidacaoCriar();
            $campos['complemento'] = '';
            foreach ($campos as $k => $v) {
                $item->$k = $request->get($k);
            }
            $item->valor_minimo_pedido = unmaskMoney($request->get('valor_minimo_pedido'));
            $item->save();

            DB::commit();

            return redirect('/backend/franquias/listar')->with('success', 'Dados atualizados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage());
        }
    }

    public function editar(Request $request, $id)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Nova loja',
                'url' => url('/backend/franquias/criar'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_LOJAS_CRIAR,
            ],
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => url('/backend/franquias/listar'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_LOJAS_LISTAR,
            ],
        ]);

        try {
            $item = Loja::findOrFail($id);

            return view('backend.lojas.editar', compact('item', 'quick_actions'));
        } catch (\Exception $ex) {
            return redirect('/backend/franquias/listar')->withErrors('Loja não encontrada');
        }
    }

    public function fazerPedido($id)
    {
        $loja = Loja::findOrFail($id);
        $loja->fazer_pedido = ! $loja->fazer_pedido;
        $loja->save();

        return redirect()->back()->with('success', $loja->fazer_pedido ? 'Pedido liberado' : 'Pedido bloqueado');
    }
}
