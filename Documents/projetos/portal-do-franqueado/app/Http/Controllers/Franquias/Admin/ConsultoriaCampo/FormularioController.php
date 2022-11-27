<?php

namespace App\Http\Controllers\Franquias\Admin\ConsultoriaCampo;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConsultoriaCampo\Topico;
use App\Models\ConsultoriaCampo\Pergunta;
use App\Models\ConsultoriaCampo\Formulario;

class FormularioController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.consultoria-campo.formularios.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quick_actions = $this->quickActionButtons([
            'dashboard' => [
                'title' => 'Voltar para o setup',
                'url' => route('admin.consultoria-de-campo'),
                'icon' => 'fa fa-dashboard',
                'resource' => '',
            ],
            'create' => [
                'title' => 'Criar formulário',
                'url' => route('admin.consultoria-de-campo.setup.formularios.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR,
            ],
        ]);

        $lista = Formulario::paginate(10);

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'quick_actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_actions = $this->quickActionButtons([
            'dashboard' => [
                'title' => 'Voltar para o setup',
                'url' => route('admin.consultoria-de-campo'),
                'icon' => 'fa fa-dashboard',
                'resource' => '',
            ],
            'create' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.consultoria-de-campo.setup.formularios.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR,
            ],
        ]);

        $topicos = Topico::orderBy('descricao')->get();

        return view(self::VIEWS_PATH . 'criar', compact('topicos', 'quick_actions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'descricao' => 'required',
            'topico.*.descricao' => 'required',
            'topico.*.pergunta.*.descricao' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $formulario = Formulario::create(['descricao' => $request->get('descricao')]);
            $topicos_id = [];
            foreach ($request->input('topico') as $topicos) {
                $topico = Topico::create(['descricao' => $topicos['descricao']]);
                $topicos_id[] = $topico->id;
                foreach ($topicos['pergunta'] as $pergunta) {
                    $topico->perguntas()->create([
                        'pergunta' => $pergunta['descricao'],
                        'pontuacao' => 1,
                    ]);
                }
            }

            $formulario->topicos()->sync($topicos_id);
            DB::commit();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.index')->with('success', 'Formulário cadastrado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.create')->withErrors($ex->getMessage());
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
        $item = Formulario::findOrFail($id);
        if ($item->visitas->count() > 0) {
            return redirect()->route('admin.consultoria-de-campo.setup.formularios.index')->withErrors('Este formulário não pode ser editado');
        }

        return view(self::VIEWS_PATH . 'editar', compact('item'));
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
            'descricao' => 'required',
            'topico.*.descricao' => 'required',
            'topico.*.pergunta.*.descricao' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $form = Formulario::findOrFail($id);
            $form->descricao = $request->get('descricao');
            $form->save();

            $topicos_id = [];
            foreach ($request->input('topico') as $topico_id => $topicos) {
                $topico = Topico::find($topico_id);
                if ($topico) {
                    $topicos_id[] = $topico->id;
                    $topico->descricao = $topicos['descricao'];
                    $topico->save();
                    foreach ($topicos['pergunta'] as $pergunta_id => $pergunta) {
                        if ($topico->perguntas->pluck('id')->contains($pergunta_id)) {
                            $p = Pergunta::findOrFail($pergunta_id);
                            $p->pergunta = $pergunta['descricao'];
                            $p->save();
                        } else {
                            $topico->perguntas()->create([
                                'pergunta' => $pergunta['descricao'],
                                'pontuacao' => 1,
                            ]);
                        }
                    }
                } else {
                    $topico = Topico::create(['descricao' => $topicos['descricao']]);
                    $topicos_id[] = $topico->id;
                    foreach ($topicos['pergunta'] as $pergunta) {
                        $topico->perguntas()->create([
                            'pergunta' => $pergunta['descricao'],
                            'pontuacao' => 1,
                        ]);
                    }
                }
            }
            $form->topicos()->sync($topicos_id);

            DB::commit();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.index')->with('success', 'Formulário editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.edit', $id)->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removerPerguntaAjax(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = Pergunta::findOrFail($id);
            $item->delete();
            DB::commit();

            return response()->json(['success' => 'true', 'message' => 'Pergunta removida com sucesso']);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json(['success' => 'false', 'message' => 'Erro ao remover a pergunta ' . $ex->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removerTopicoAjax(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $item = Topico::findOrFail($id);
            $item->delete();
            DB::commit();

            return response()->json(['success' => 'true', 'message' => 'Tópico removido com sucesso']);
        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json(['success' => 'false', 'message' => 'Erro ao remover o tópico' . $ex->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Formulario::findOrFail($id);
            $item->delete();

            DB::commit();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.index')->with('success', 'Formulário deletado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup.formularios.index')->withErrors($ex->getMessage());
        }
    }
}
