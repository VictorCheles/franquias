<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClienteLojaEstabelecimento;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteLojaEstabelecimentoController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.cliente-loja.estabelecimento.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = ClienteLojaEstabelecimento::orderBy('nome');
        if ($request->ajax()) {
            return request()->json(['data' => $lista->get()->toArray()]);
        } else {
            $lista = $lista->paginate(10);

            return view(self::VIEWS_PATH . 'listar', compact('lista'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $view = 'criar';
        if ($request->ajax()) {
            $view = '_criar';
        }

        return view(self::VIEWS_PATH . $view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $item = ClienteLojaEstabelecimento::create([
                'nome' => $request->get('nome'),
                'user_id' => Auth()->user()->id,
            ]);

            DB::commit();
            if ($request->ajax()) {
                return response()->json(['data' => $item]);
            } else {
                return redirect()->route('clientes_loja_estabelecimento.index')->with('success', 'Estabelecimento cadastrado com sucesso');
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['message' => $ex->getMessage()], 500);
            } else {
                return redirect()->route('clientes_loja_estabelecimento.create')->withErrors($ex->getMessage())->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = ClienteLojaEstabelecimento::findOrFail($id);

            return view(self::VIEWS_PATH . 'editar', compact('item'));
        } catch (\Exception $exception) {
            return redirect()->route('clientes_loja_estabelecimento.index')->withErrors($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nome' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $item = ClienteLojaEstabelecimento::findOrFail($id);
            $item->nome = $request->get('nome');
            $item->user_id = Auth()->user()->id;
            $item->save();

            DB::commit();

            return redirect()->route('clientes_loja_estabelecimento.index')->with('success', 'Estabelecimento editado com sucesso');
        } catch (ModelNotFoundException $exception) {
            DB::rollBack();

            return redirect()->route('clientes_loja_estabelecimento.index')->withErrors($exception->getMessage());
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('clientes_loja_estabelecimento.edit', $item->id)->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
