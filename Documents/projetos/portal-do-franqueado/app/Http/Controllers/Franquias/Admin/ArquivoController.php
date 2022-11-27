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

class ArquivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_ARQUIVOS)->only(['index', 'download']);
        $this->middleware('acl:' . Recurso::ADM_ARQUIVOS_LISTAR)->only(['indexAdmin']);
        $this->middleware('acl:' . Recurso::ADM_ARQUIVOS_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_ARQUIVOS_CRIAR)->only(['create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $lista = Pasta::with('arquivos')->where(function ($q) use ($request, $id) {
            $q->where('setor_id', $id);
        })->paginate(10);

        return view('portal-franqueado.admin.arquivo.listar', compact('lista', 'id'));
    }

    public function indexAdmin(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo arquivo',
                'url' => route('admin.arquivo.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_ARQUIVOS_CRIAR,
            ],
        ]);

        $lista = Arquivo::where(function ($q) use ($request) {
            if($nome = $request->input('filter.nome')) {
                $q->where('nome', 'ilike', "%{$nome}%");
            }
            if($pasta_id = $request->input('filter.pasta_id')) {
                $q->wherePastaId($pasta_id);
            }
            if($setor_id = $request->input('filter.setor_id')) {
                $q->whereHas('pasta', function($q) use ($setor_id){
                    $q->whereSetorId($setor_id);
                });
            }
            if($tipo = $request->input('filter.tipo')) {
                $q->where('arquivo', 'ilike', "%{$tipo}%");
            }
        })->orderby('created_at', 'desc')->paginate(10);

        return view('portal-franqueado.admin.arquivo.listar-admin', compact('lista', 'quick_actions'));
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
                'url' => url('admin/arquivo/lista/admin'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_ARQUIVOS_LISTAR,
            ],
        ]);

        $listKv = [];
        $pastas = Pasta::orderBy('setor_id')->orderBy('nome')->get()->each(function (Pasta &$item) use (&$listKv) {
            $listKv[$item->id] = $item->nome . ' - ' . $item->setor;
        });

        return view('portal-franqueado.admin.arquivo.criar', compact('listKv', 'quick_actions'));
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
            'arquivo' => 'required|file',
            'nome' => 'required',
            'descricao' => 'required',
            'pasta_id' => 'required|exists:pastas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.arquivo.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->hasFile('arquivo') and $request->file('arquivo')->isValid()) {
                $arquivo = makeFileName($request, 'arquivo');
                $request->file('arquivo')->move('uploads/arquivos', $arquivo);
            } else {
                throw new \Exception('O arquivo não é válido');
            }

            Arquivo::create([
                'nome' => $request->get('nome'),
                'descricao' => $request->get('descricao'),
                'arquivo' => $arquivo,
                'pasta_id' => $request->get('pasta_id'),
            ]);

            DB::commit();

            return redirect()->route('admin.arquivo.index.admin')->with('success', 'Arquivo criado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.arquivo.create')->withErrors($ex->getMessage())->withInput();
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
            $item = Arquivo::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.arquivo.index')->withErrors($ex->getMessage())->withInput();
        }

        $listKv = [];
        $pastas = Pasta::orderBy('setor_id')->orderBy('nome')->get()->each(function (Pasta &$item) use (&$listKv) {
            $listKv[$item->id] = $item->nome . ' - ' . $item->setor;
        });

        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para lista',
                'url' => url('admin/arquivo/lista/admin'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_ARQUIVOS_LISTAR,
            ],
            'create' => [
                'title' => 'Novo arquivo',
                'url' => route('admin.arquivo.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_ARQUIVOS_CRIAR,
            ],
        ]);

        return view('portal-franqueado.admin.arquivo.editar', compact('item', 'listKv', 'quick_actions'));
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
            $item = Arquivo::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.arquivo.index')->withErrors($ex->getMessage())->withInput();
        }

        $validator = Validator::make($request->all(), [
            'arquivo' => 'file',
            'nome' => 'required',
            'descricao' => 'required',
            'pasta_id' => 'required|exists:pastas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.arquivo.create')->withErrors($validator)->withInput();
        }

        try {
            if ($request->hasFile('arquivo') and $request->file('arquivo')->isValid()) {
                @unlink('uploads/arquivos/' . $item->arquivo);
                $arquivo = makeFileName($request, 'arquivo');
                $request->file('arquivo')->move('uploads/arquivos', $arquivo);
                $item->arquivo = $arquivo;
            }

            $item->nome = $request->get('nome');
            $item->descricao = $request->get('descricao');
            $item->pasta_id = $request->get('pasta_id');
            $item->save();

            return redirect()->route('admin.arquivo.index.admin')->with('success', 'Arquivo editado com sucesso!');
        } catch (\Exception $ex) {
            return redirect()->route('admin.arquivo.edit', $item->id)->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $item = Arquivo::findOrFail($id);
            @unlink('uploads/arquivos/' . $item->arquivo);
            ArquivoUsuario::where('arquivo_id', $item->id)->delete();
            $item->delete();

            return redirect()->route('admin.arquivo.index.admin')->with('success', 'Arquivo deletado com sucesso');
        } catch (\Exception $ex) {
            return redirect()->route('admin.arquivo.index.admin')->withErrors($ex->getMessage());
        }
    }

    public function download(Request $request, $id)
    {
        try {
            $item = Arquivo::findOrFail($id);
            $download = ArquivoUsuario::firstOrCreate([
                'arquivo_id' => $item->id,
                'user_id' => $request->user()->id,
            ]);
            $download->downloads++;
            $download->save();

            return response()->download(public_path(). '/uploads/arquivos/' . $item->arquivo);
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors('Arquivo não encontrado ou corrompido [#' . $item->id .' - '. $item->nome . ']');
        }
    }
}
