<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\ACL\Grupo;
use App\ACL\Recurso;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GrupoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:'. implode(',', [Recurso::ADM_GRUPOS_LISTAR]))->only(['index']);
        $this->middleware('acl:'. implode(',', [Recurso::ADM_GRUPOS_CRIAR]))->only(['create', 'store']);
        $this->middleware('acl:'. implode(',', [Recurso::ADM_GRUPOS_EDITAR]))->only(['edit']);
        $this->middleware('acl:'. implode(',', [Recurso::ADM_GRUPOS_DELETAR]))->only(['destroy']);
    }

    public function index()
    {
        $quick_actions = $this->quickActionButtons([
//            'index' => [
//                'title' => 'Voltar para lista',
//                'url' => route('admin.grupos.index'),
//                'icon' => 'fa fa-arrow-left',
//                'resource' => \App\ACL\Recurso::ADM_GRUPOS_LISTAR,
//            ],
            'create' => [
                'title' => 'Novo Grupo',
                'url' => route('admin.grupos.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_GRUPOS_CRIAR,
            ],
        ]);

        $lista = Grupo::where(function () {
        })->orderBy('nome')->paginate(10);

        return view('portal-franqueado.admin.grupos.listar', compact('lista', 'quick_actions'));
    }

    public function create()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para lista',
                'url' => route('admin.grupos.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_GRUPOS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.grupos.criar', compact('quick_actions'));
    }

    public function store(Requests\StoreACLGrupoRequest $request)
    {
        try {
            $grupo = Grupo::create([
                'nome' => $request->get('nome'),
            ]);

            $grupo->recursos()->sync($request->get('recurso'));

            DB::commit();

            return redirect()->route('admin.grupos.index')->with('success', 'Grupo criado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para lista',
                'url' => route('admin.grupos.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_GRUPOS_LISTAR,
            ],
            'create' => [
                'title' => 'Novo Grupo',
                'url' => route('admin.grupos.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_GRUPOS_CRIAR,
            ],
        ]);
        try {
            $item = Grupo::findOrFail($id);
            $default_recursos = $item->recursos->pluck('id');

            return view('portal-franqueado.admin.grupos.editar', compact('quick_actions', 'item', 'default_recursos'));
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    public function update(Requests\StoreACLGrupoRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = Grupo::findOrFail($id);
            $item->nome = $request->get('nome');
            $item->save();
            $item->recursos()->sync($request->get('recurso'));
            DB::commit();

            return redirect()->route('admin.grupos.index')->with('success', 'Grupo editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Grupo::findOrFail($id);
            $item->delete();
            DB::commit();

            return redirect()->route('admin.grupos.index')->with('success', 'Grupo deletado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
