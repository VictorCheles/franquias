<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Setor;
use App\Models\Notificacao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SolicitacaoHistorico;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SetorController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_SETORES_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_SETORES_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_SETORES_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_SETORES_DELETAR)->only(['destroy']);
        $this->middleware('acl:' . Recurso::ADM_SETORES_REMANEJAR_SOLICITACOES)->only(['destroyRemanejamento']);
        $this->middleware('acl:' . Recurso::ADM_SETORES_DELETAR_SOLICITACOES)->only(['destroyCascade']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo setor',
                'url' => route('admin.setor.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_SETORES_CRIAR,
            ],
        ]);

        $lista = Setor::where(function ($q) use ($request) {
            if ($request->input('filter.nome')) {
                $q->where('nome', 'ilike', "%{$request->input('filter.nome')}%");
            }
            if ($responsavel = $request->input('filter.responsavel')) {
                $q->whereHas('responsaveis', function ($qq) use ($responsavel) {
                    $qq->where('user_id', $responsavel);
                });
            }
        })->orderBy('nome', 'asc')->paginate(10);

        return view('portal-franqueado.admin.setor.listar', compact('lista', 'quick_actions'));
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
                'url' => route('admin.setor.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_SETORES_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.setor.criar', compact('quick_actions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'tag' => 'required|between:4,4',
            'interno' => 'required|boolean',
            'responsaveis.*' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.setor.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $tag = str_replace('#', '', mb_strtoupper($request->get('tag')));

            $setor = Setor::create([
                'nome' => $request->get('nome'),
                'tag' => $tag,
                'interno' => $request->get('interno'),
            ]);

            if ($responsaveis = $request->get('responsaveis')) {
                $setor->responsaveis()->sync($responsaveis);
            }

            DB::commit();

            return redirect()->route('admin.setor.index')->with('success', 'Setor criado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.setor.create')->withErrors($ex->getMessage())->withInput();
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
        //@todo nada para fazer aqui
        return redirect(route('admin.setor.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = Setor::findOrFail($id);
            $responsaveis = [];
            if ($item->responsaveis->count() > 0) {
                $item->responsaveis->each(function ($item) use (&$responsaveis) {
                    $responsaveis[] = $item->id;
                });
            }
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('admin.setor.index')->withErrors('Setor não encontrada');
        } catch (\Exception $ex) {
            return redirect()->route('admin.setor.edit', $item->id)->withErrors('Setor não encontrada');
        }

        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para lista',
                'url' => route('admin.setor.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_SETORES_LISTAR,
            ],
            'create' => [
                'title' => 'Novo setor',
                'url' => route('admin.setor.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_SETORES_CRIAR,
            ],
        ]);

        return view('portal-franqueado.admin.setor.editar', compact('item', 'responsaveis', 'quick_actions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $item = Setor::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.setor.index'))->withErrors($ex->getMessage());
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'tag' => 'required|between:4,4',
            'interno' => 'required|boolean',
            'responsaveis.*' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.setor.edit', $item->id))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $tag = str_replace('#', '', mb_strtoupper($request->get('tag')));
            $item->nome = $request->get('nome');
            $item->tag = $tag;
            $item->interno = $request->get('interno');
            $item->responsaveis()->sync($request->get('responsaveis'));
            $item->save();

            DB::commit();

            return redirect(route('admin.setor.index'))->with('success', 'Setor editado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = Setor::findOrFail($id);
            if ($item->solicitacoes->count() == 0) {
                $item->delete();

                return redirect()->route('admin.setor.index')->with('success', 'Setor excluido com sucesso!');
            } else {
                $tituloPagina = 'Setor com solicitações!';
                $subTituloPagina = $item->nome;
                $setores = Setor::where('id', '!=', $item->id)->orderBy('nome')->lists('nome', 'id');

                return view('portal-franqueado.admin.setor.deletar-com-solicitacoes', compact('tituloPagina', 'subTituloPagina', 'item', 'setores'));
            }
        } catch (\Exception $ex) {
            return redirect()->route('admin.setor.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyRemanejamento(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'setor_id' => 'required|exists:setores,id',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->all());
            }
            $item = Setor::findOrFail($id);
            Solicitacao::where('setor_id', $item->id)->update(['setor_id' => $request->get('setor_id')]);
            $item->delete();
            DB::commit();

            return redirect()->route('admin.setor.index')->with('success', 'Setor excluido e solicitações remanejadas com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.setor.index')->withErrors($ex->getMessage());
        }
    }

    public function destroyCascade($id)
    {
        DB::beginTransaction();
        try {
            $item = Setor::findOrFail($id);
            $item->solicitacoes->each(function (Solicitacao $solicitacao) {
                if (! is_null($solicitacao->anexos)) {
                    foreach ($solicitacao->anexos as $anexo) {
                        @unlink('uploads/solicitacao/' . $anexo);
                    }
                }
                Notificacao::where('atributos->solicitacao_id', $solicitacao->id)->delete();
                SolicitacaoHistorico::where('solicitacao_id', $solicitacao->id)->delete();
                $solicitacao->delete();
            });
            $item->delete();
            DB::commit();

            return redirect()->route('admin.setor.index')->with('success', 'Setor e solicitações deletados com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.setor.index')->withErrors($ex->getMessage());
        }
    }
}
