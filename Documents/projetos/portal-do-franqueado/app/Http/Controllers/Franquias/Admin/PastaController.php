<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Pasta;
use App\Models\Arquivo;
use Illuminate\Http\Request;
use App\Models\ArquivoUsuario;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PastaController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_PASTAS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_PASTAS_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_PASTAS_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_PASTAS_DELETAR)->only(['destroy']);
        $this->middleware('acl:' . Recurso::ADM_PASTAS_ARQUIVOS_DELETAR)->only(['destroyCascade']);
        $this->middleware('acl:' . Recurso::ADM_PASTAS_ARQUIVOS_REMANEJAR)->only(['destroyRemanejamento']);
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
                'title' => 'Nova pasta',
                'url' => route('admin.pasta.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PASTAS_CRIAR,
            ],
        ]);

        $lista = Pasta::where(function ($q) {
        })->orderby('nome')->paginate(10);

        return view('portal-franqueado.admin.pasta.listar', compact('lista', 'quick_actions'));
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
                'url' => route('admin.pasta.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PASTAS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.pasta.criar', compact('quick_actions'));
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
            'setor_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pasta.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            Pasta::create([
                'nome' => $request->get('nome'),
                'setor_id' => $request->get('setor_id'),
            ]);

            DB::commit();

            return redirect()->route('admin.pasta.index')->with('success', 'Pasta criada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.pasta.create')->withErrors($ex->getMessage())->withInput();
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
            $item = Pasta::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.pasta.index')->withErrors($ex->getMessage())->withInput();
        }

        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para lista',
                'url' => route('admin.pasta.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PASTAS_LISTAR,
            ],
            'create' => [
                'title' => 'Nova pasta',
                'url' => route('admin.pasta.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PASTAS_CRIAR,
            ],
        ]);

        return view('portal-franqueado.admin.pasta.editar', compact('item', 'quick_actions'));
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
            $item = Pasta::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.pasta.index')->withErrors($ex->getMessage())->withInput();
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'setor_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pasta.edit', $item->id)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $item->nome = $request->get('nome');
            $item->setor_id = $request->get('setor_id');
            $item->save();
            DB::commit();

            return redirect()->route('admin.pasta.index')->with('success', 'Pasta editada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.pasta.edit', $item->id)->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $item = Pasta::findOrFail($id);
            if ($item->arquivos->count() == 0) {
                $item->delete();

                return redirect()->route('admin.pasta.index')->with('success', 'Pasta removida com sucesso');
            } else {
                $listKv = [];
                $pastas = Pasta::where('id', '!=', $item->id)->orderBy('setor_id')->orderBy('nome')->get()->each(function (Pasta &$item) use (&$listKv) {
                    $listKv[$item->id] = $item->nome . ' - ' . $item->setor;
                });

                $quick_actions = $this->quickActionButtons([
                    'index' => [
                        'title' => 'Voltar para lista',
                        'url' => route('admin.pasta.index'),
                        'icon' => 'fa fa-arrow-left',
                        'resource' => \App\ACL\Recurso::ADM_SETORES_LISTAR,
                    ],
                ]);

                return view('portal-franqueado.admin.pasta.deletar-com-arquivos', compact('item', 'listKv', 'quick_actions'));
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.pasta.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyCascade($id)
    {
        DB::beginTransaction();
        try {
            $item = Pasta::findOrFail($id);
            $item->arquivos->each(function (Arquivo $arquivo) {
                @unlink('uploads/arquivos/' . $arquivo->arquivo);
                ArquivoUsuario::where('arquivo_id', $arquivo->id)->delete();
                $arquivo->delete();
            });
            $item->delete();
            DB::commit();

            return redirect()->route('admin.pasta.index')->with('success', 'Pasta e arquivos deletados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.pasta.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyRemanejamento(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'pasta_id' => 'required|exists:pastas,id',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->all());
            }
            $item = Pasta::findOrFail($id);
            Arquivo::where('pasta_id', $item->id)->update(['pasta_id' => $request->get('pasta_id')]);
            $item->delete();
            DB::commit();

            return redirect()->route('admin.pasta.index')->with('success', 'Pasta deletada e arquivos remanejados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.pasta.index')->withErrors($ex->getMessage());
        }
    }
}
