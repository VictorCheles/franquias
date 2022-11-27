<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriaProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_CATEGORIAS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_CATEGORIAS_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_CATEGORIAS_EDITAR)->only(['edit']);
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
                'title' => 'Nova categoria',
                'url' => route('admin.categoria.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_CATEGORIAS_CRIAR,
            ],
        ]);

        $lista = CategoriaProduto::orderBy('nome')->paginate(10);

        return view('portal-franqueado.admin.categoria-produto.listar', compact('quick_actions', 'lista'));
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
                'title' => 'Voltar para lista',
                'url' => route('admin.categoria.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_CATEGORIAS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.categoria-produto.criar', compact('quick_actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'disponivel' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.categoria.create'))->withErrros($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            CategoriaProduto::create([
                'nome' => $request->get('nome'),
                'disponivel' => $request->get('disponivel'),
            ]);
            DB::commit();

            return redirect(route('admin.categoria.index'))->with('success', 'Categoria criada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.categoria.create'))->withErrros($ex->getMessage())->withInput();
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
                    'title' => 'Voltar para lista',
                    'url' => route('admin.categoria.index'),
                    'icon' => 'fa fa-arrow-left',
                    'resource' => \App\ACL\Recurso::ADM_CATEGORIAS_LISTAR,
                ],
                'create' => [
                    'title' => 'Nova categoria',
                    'url' => route('admin.categoria.create'),
                    'icon' => 'fa fa-plus',
                    'resource' => \App\ACL\Recurso::ADM_CATEGORIAS_CRIAR,
                ],
            ]);
            $item = CategoriaProduto::findOrFail($id);

            return view('portal-franqueado.admin.categoria-produto.editar', compact('quick_actions', 'item'));
        } catch (ModelNotFoundException $ex) {
            return redirect(route('admin.categoria.index'))->withErrors('Categoria não encontrada');
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
            $item = CategoriaProduto::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect(route('admin.categoria.index'))->withErrors('Categoria não encontrada');
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'disponivel' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.categoria.edit', $item->id))->withErrors($validator);
        }

        $item->nome = $request->get('nome');
        $item->disponivel = $request->get('disponivel');
        $item->save();

        return redirect(route('admin.categoria.index'))->with('success', 'Categoria editada com sucesso');
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
