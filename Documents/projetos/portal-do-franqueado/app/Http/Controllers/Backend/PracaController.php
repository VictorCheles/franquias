<?php

namespace App\Http\Controllers\Backend;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Praca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PracaController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_PRACAS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_PRACAS_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_PRACAS_CRIAR)->only(['create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Nova Praça',
                'url' => route('backend.praca.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PRACAS_CRIAR,
            ],
        ]);

        $lista = Praca::where(function () {
        })->paginate(10);

        return view('backend.praca.listar', compact('quick_actions', 'lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para listagem',
                'url' => route('backend.praca.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PRACAS_LISTAR,
            ],
        ]);

        return view('backend.praca.criar', compact('quick_actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'data_limite_pedido' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('backend.praca.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $praca = Praca::create([
                'nome' => $request->get('nome'),
                'data_limite_pedido' => $request->get('data_limite_pedido'),
            ]);
            $praca->criarEventoCalendario();

            DB::commit();

            return redirect()->route('backend.praca.index')->with('success', 'Praça criada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('backend.praca.create')->withErrors($ex->getMessage())->withInput();
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
            $quick_actions = $this->quickActionButtons([
                'index' => [
                    'title' => 'Voltar para listagem',
                    'url' => route('backend.praca.index'),
                    'icon' => 'fa fa-arrow-left',
                    'resource' => \App\ACL\Recurso::ADM_PRACAS_LISTAR,
                ],
                'create' => [
                    'title' => 'Nova Praça',
                    'url' => route('backend.praca.create'),
                    'icon' => 'fa fa-plus',
                    'resource' => \App\ACL\Recurso::ADM_PRACAS_CRIAR,
                ],
            ]);
            $item = Praca::findOrFail($id);

            return view('backend.praca.editar', compact('quick_actions', 'item'));
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('backend.praca.index')->withErrors($ex->getModel());
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
        try {
            $item = Praca::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('backend.praca.index')->withErrors($ex->getModel());
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'data_limite_pedido' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('backend.praca.update', $item->id)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item->nome = $request->get('nome');
            $item->data_limite_pedido = $request->get('data_limite_pedido');
            $item->save();
            $item->criarEventoCalendario();

            DB::commit();

            return redirect()->route('backend.praca.index')->with('success', 'Praça editada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('backend.praca.edit', $item->id)->withErrors($ex->getMessage())->withInput();
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
