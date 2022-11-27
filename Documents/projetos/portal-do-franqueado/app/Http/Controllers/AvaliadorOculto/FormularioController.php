<?php

namespace App\Http\Controllers\AvaliadorOculto;

use App\Models\AvaliadorOculto\User;
use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AvaliadorOculto\Pergunta;
use App\Models\AvaliadorOculto\Resposta;
use App\Jobs\AvaliadorOcultoRespostaEmail;
use App\Models\AvaliadorOculto\Formulario;
use App\Models\AvaliadorOculto\RespostaSubjetiva;

class FormularioController extends Controller
{
    const VIEWS_PATH = 'avaliador-oculto.admin.formularios.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_CRIAR)->only(['create', 'store']);
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR)->only(['edit', 'update']);
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR)->only(['destroy']);
    }

    public function index()
    {
        $quick_actions = $this->quickActionButtons([
            'ranking' => [
                'title' => 'Gerar ranking',
                'url' => route('avaliadoroculto.ranking'),
                'icon' => 'fa fa-bar-chart',
                'resource' => Recurso::ADM_AVALIADOR_OCULTO_DASHBOARD,
            ],
        ]);

        $lista = Formulario::orderBy('status')->orderBy('created_at', 'desc')->paginate(10);

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'quick_actions'));
    }

    public function create()
    {
        $lojas = Loja::orderBy('nome')->pluck('nome', 'id');

        return view(self::VIEWS_PATH . 'criar', compact('lojas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'lojas' => 'required|array',
            'pergunta.*' => 'required',
            'pergunta_tipo.*' => 'required',
            'pergunta_peso.*' => 'required',
            'pergunta_peso_negativo.*' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.formularios.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $formulario = Formulario::create([
                'nome' => $request->get('nome'),
                'status' => $request->get('status'),
            ]);
            $formulario->lojas()->sync($request->get('lojas'));

            foreach ($request->get('pergunta') as $k => $pergunta) {
                $tipo = $request->get('pergunta_tipo')[$k];
                $peso = $request->get('pergunta_peso')[$k];
                $peso_negativo = $request->get('pergunta_peso_negativo')[$k];
                Pergunta::create([
                    'pergunta' => $pergunta,
                    'formulario_id' => $formulario->id,
                    'tipo' => $tipo,
                    'peso' => $peso,
                    'peso_negativo' => $peso_negativo,
                ]);
            }

            DB::commit();

            return redirect()->route('avaliadoroculto.formularios.index')->with('success', 'Formulário criado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.formularios.create')->withErrors($ex->getMessage())->withInput();
        }
    }

    public function show($id)
    {
    }

    public function edit(Request $request, $id)
    {
        $editTemplate = 'editar';
        try {
            $item = Formulario::findOrFail($id);
            if ($item->users->count() > 0) {
                $editTemplate = 'editar-so-peso';
                $request->session()->flash('warning', 'Este formulário já foi respondido, e por isso só o <b>peso das perguntas</b> poderão ser modificados');
            }

            $lojas = Loja::orderBy('nome')->pluck('nome', 'id');

            return view(self::VIEWS_PATH . $editTemplate, compact('item', 'lojas'));
        } catch (\Exception $exception) {
            return redirect()->route('avaliadoroculto.formularios.index')->withErrors($exception->getMessage());
        }
    }

    public function toggleActive($id)
    {
        DB::beginTransaction();
        try {
            $formulario = Formulario::findOrFail($id);
            if ($formulario->status == Formulario::STATUS_ATIVO) {
                $formulario->status = Formulario::STATUS_INATIVO;
                $mensagem = 'Formulário desabilitado com sucesso';
            } else {
                $formulario->status = Formulario::STATUS_ATIVO;
                $mensagem = 'Formulário habilitado com sucesso';
            }

            $formulario->save();

            DB::commit();

            return redirect()->route('avaliadoroculto.formularios.index')->with('success', $mensagem);
        } catch (\Exception $exception) {
            return redirect()->route('avaliadoroculto.formularios.index')->withErrors($exception->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = Formulario::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect()->route('avaliadoroculto.formularios.index')->withErrors($ex->getMessage())->withInput();
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'lojas' => 'required|array',
            'pergunta.*' => 'required',
            'pergunta_tipo.*' => 'required',
            'pergunta_peso.*' => 'required',
            'pergunta_peso_negativo.*' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.formularios.edit', $item->id)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $item->nome = $request->get('nome');
            $item->status = $request->get('status');
            $item->lojas()->sync($request->get('lojas'));
            $item->save();

            foreach ($request->get('pergunta') as $k => $pergunta) {
                $p = Pergunta::findOrFail($k);
                $p->pergunta = $pergunta;
                $p->tipo = $request->get('pergunta_tipo')[$k];
                $p->peso = $request->get('pergunta_peso')[$k];
                $p->peso_negativo = $request->get('pergunta_peso_negativo')[$k];
                $p->save();
            }

            DB::commit();

            return redirect()->route('avaliadoroculto.formularios.index')->with('success', 'Formulário editado com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.formularios.edit', $item->id)->withErrors($exception->getMessage())->withInput();
        }
    }

    public function updatePeso(Request $request, $id)
    {
        try {
            $item = Formulario::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect()->route('avaliadoroculto.formularios.index')->withErrors($ex->getMessage())->withInput();
        }

        $validator = Validator::make($request->all(), [
            'pergunta_peso.*' => 'required',
            'pergunta_peso_negativo.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('avaliadoroculto.formularios.edit', $item->id)->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            foreach ($request->get('pergunta_peso') as $k => $peso) {
                $p = Pergunta::findOrFail($k);
                $p->peso = $peso;
                $p->peso_negativo = $request->get('pergunta_peso_negativo')[$k];
                $p->save();
            }

            DB::commit();

            return redirect()->route('avaliadoroculto.formularios.index')->with('success', 'Formulário editado com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.formularios.edit', $item->id)->withErrors($exception->getMessage())->withInput();
        }
    }

    public function ajaxRemoverPergunta(Request $request)
    {
        DB::beginTransaction();
        try {
            $item = Pergunta::findOrFail($request->get('pergunta'));
            $item->delete();
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    public function ajaxAdicionarPergunta(Request $request)
    {
        try {
            $pergunta = Pergunta::create([
                'pergunta' => $request->get('pergunta'),
                'tipo' => $request->get('tipo'),
                'formulario_id' => $request->get('formulario'),
                'peso' => $request->get('peso'),
                'peso_negativo' => $request->get('peso_negativo'),
            ]);

            return response()->json(['id' => $pergunta->id]);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $item = Formulario::findOrFail($id);

        DB::beginTransaction();

        try {
            $ids_perguntas = $item->perguntas->pluck('id');
            if ($ids_perguntas->count() > 0) {
                Resposta::whereIn('pergunta_id', $ids_perguntas->toArray())->delete();
                RespostaSubjetiva::whereIn('pergunta_id', $ids_perguntas->toArray())->delete();
            }
            DB::table('avaliador_oculto_usuarios_formularios')->where('formulario_id', $item->id)->delete();
            DB::table('avaliador_oculto_lojas_formularios')->where('formulario_id', $item->id)->delete();
            $item->perguntas()->delete();
            $item->delete();
            DB::commit();

            return redirect()->route('avaliadoroculto.formularios.index')->with('success', 'Formulário deletado com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('avaliadoroculto.formularios.index')->withErrors($exception->getMessage());
        }
    }

    public function responder(Request $request)
    {
        try {
            $user = Auth()->guard('avaliador_oculto')->user();
            $formulario = $user->formularios()->whereId($request->get('formulario_id'))->first();
        } catch (\Exception $ex) {
            return redirect()->route('dashboard')->withErrors($ex->getMessage())->withInput();
        }

        $rules = [
            'loja_id' => 'required',
            'formulario_id' => 'required',
            'observacoes' => 'required',
            'foto_loja' => 'required|file',
            'foto_consumo' => 'required|file',
        ];

        $formulario->perguntas->each(function (Pergunta $pergunta) use (&$rules) {
            $rules['perguntas.' . $pergunta->id] = 'required';
        });

        $validator = Validator::make($request->all(), $rules, ['perguntas.*' => 'todas as perguntas devem ser respondidas']);

        if ($validator->fails()) {
            return redirect()->route('dashboard')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto_loja') and $request->file('foto_loja')->isValid()) {

                $foto_loja = makeFileName($request, 'foto_loja');
                $request->file('foto_loja')->move('uploads/cliente_oculto_comprovantes', $foto_loja);

                $foto_consumo = makeFileName($request, 'foto_consumo');
                $request->file('foto_consumo')->move('uploads/cliente_oculto_comprovantes', $foto_consumo);
            } else {
                throw new \Exception('O comprovante não é válido');
            }

            DB::table('avaliador_oculto_usuarios_formularios')
                ->where('formulario_id', $request->get('formulario_id'))
                ->where('loja_id', $request->get('loja_id'))
                ->where('user_id', $user->id)
                ->update([
                        'observacoes' => $request->get('observacoes'),
                        'respondido_em' => date('Y-m-d H:i:s'),
                        'finalizou' => true,
                        'foto_loja' => $foto_loja,
                        'foto_consumo' => $foto_consumo,
                        'data_termino' => date('Y-m-d H:i:s'),
                ]);

            $formulario->perguntas->each(function (Pergunta $pergunta) use ($request, $user) {
                if ($pergunta->tipo == Pergunta::TIPO_SIM_NAO) {
                    $class = Resposta::class;
                } elseif ($pergunta->tipo == Pergunta::TIPO_SUBJETIVA) {
                    $class = RespostaSubjetiva::class;
                } else {
                    throw new \Exception('Tipo de resposta inválida');
                }

                $class::create([
                    'loja_id' => $request->get('loja_id'),
                    'user_id' => $user->id,
                    'pergunta_id' => $pergunta->id,
                    'resposta' => $request->get('perguntas')[$pergunta->id],
                ]);
            });

            DB::commit();

            $this->dispatch(new AvaliadorOcultoRespostaEmail($user, $formulario));

            return redirect()->route('dashboard');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('dashboard')->withErrors($exception->getMessage())->withInput();
        }
    }

    public function enviarComprovante($formulario_id, $loja_id)
    {
        $user = Auth()->guard('avaliador_oculto')->user();
        $formulario = $user->formularios()->where('id', $formulario_id)->wherePivot('loja_id', $loja_id)->first();

        if (! $formulario) {
            return redirect()->route('dashboard');
        }

        if (! $formulario->pivot->observacoes) {
            return redirect()->route('dashboard');
        }

        $loja = Loja::find($formulario->pivot->loja_id);

        return view(self::VIEWS_PATH . 'enviar-comprovante', compact('formulario', 'loja', 'formulario_id', 'loja_id'));
    }

    public function enviarComprovantePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comprovante' => 'required|file',
            'loja_id' => 'required',
            'formulario_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dashboard')->withErrors($validator);
        }

        $user = Auth()->guard('avaliador_oculto')->user();
        if ($request->hasFile('comprovante') and $request->file('comprovante')->isValid()) {
            $comprovante = makeFileName($request, 'comprovante');
            $request->file('comprovante')->move('uploads/cliente_oculto_comprovantes', $comprovante);
        } else {
            throw new \Exception('O comprovante não é válido');
        }

        DB::table('avaliador_oculto_usuarios_formularios')
            ->where('formulario_id', $request->get('formulario_id'))
            ->where('loja_id', $request->get('loja_id'))
            ->where('user_id', $user->id)
            ->update([
                'finalizou' => true,
                'foto_comprovante' => $comprovante,
                'data_termino' => date('Y-m-d H:i:s'),
            ]);

        return redirect()->route('dashboard');
    }

    public function removerFormulario(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));
        $user->formularios()->detach($request->get('formulario_id'));
        return redirect()->back()->with('success', 'Visita cancelada com sucesso');
    }

    public function resetarFormulario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'formulario_id' => 'required',
            'loja_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $formulario = Formulario::findOrFail($request->get('formulario_id'));

            $row = DB::table('avaliador_oculto_usuarios_formularios')
                ->where('user_id', $request->get('user_id'))
                ->where('formulario_id', $request->get('formulario_id'))
                ->where('loja_id', $request->get('loja_id'))->get();

            $row = reset($row);

            DB::table('avaliador_oculto_usuarios_formularios')
                ->where('user_id', $request->get('user_id'))
                ->where('formulario_id', $request->get('formulario_id'))
                ->where('loja_id', $request->get('loja_id'))
                ->update([
                    'observacoes' => null,
                    'foto_comprovante' => null,
                    'foto_loja' => null,
                    'foto_consumo' => null,
                    'finalizou' => false,
                    'data_termino' => null,
                    'respondido_em' => null,
                ]);

            collect([$row->foto_loja, $row->foto_consumo])->each(function ($field) {
                @unlink('uploads/cliente_oculto_comprovantes/' . $field);
            });

            Resposta::where('user_id', $request->get('user_id'))->whereIn('pergunta_id', $formulario->perguntas->pluck('id'))->delete();
            RespostaSubjetiva::where('user_id', $request->get('user_id'))->whereIn('pergunta_id', $formulario->perguntas->pluck('id'))->delete();
            DB::commit();

            return back()->with('success', 'Formulário resetado com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return back()->withErrors('Erro ao resetar formulário :' . $exception->getMessage());
        }
    }

    public function estatisticas(Request $request, $id)
    {
        $formulario = Formulario::with(['lojas' => function ($q) use ($request) {
            if ($loja_id = $request->input('filter.loja_id')) {
                $q->whereLojaId($loja_id);
            }
            if ($praca_id = $request->input('filter.praca_id')) {
                $q->whereHas('praca', function ($q) use ($praca_id) {
                    $q->whereId($praca_id);
                });
            }
        }])->findOrFail($id);

        $formulario->lojas->each(function ($loja) use ($formulario) {
            $loja->scorePorFormulario($formulario);
        });

        $lojas = $formulario->lojas->sortByDesc('aproveitamento');
        $holder = Formulario::findOrFail($id)->lojas;
        $lojas_filter = $holder->pluck('nome', 'id')->toArray();
        $pracas_filter = $holder->map(function ($loja) {
            return $loja->praca;
        })->unique('id')->sortBy('nome')->pluck('nome', 'id');

        return view(self::VIEWS_PATH . 'estatisticas', compact('formulario', 'lojas', 'lojas_filter', 'pracas_filter'));
    }

    public function ajaxObterListaFormulariosLoja($id)
    {
        $formularios = Formulario::getFormulariosFromLoja($id);
        $formulariosKV = $formularios->pluck('nome', 'id');

        ob_start(); ?>
        <div class="form-group">
            <?php echo \Form::label('formulario', 'Formulário'); ?>
            <?php echo \Form::select('formulario', $formulariosKV, null, ['required' => true,'placeholder' => 'Selecione um formulário','class' => 'form-control']); ?>
        </div>
        <?php

        return ob_get_clean();
    }

    public function ranking(Request $request)
    {
        $formularios_filter = Formulario::orderBy('created_at', 'desc')->pluck('nome', 'id');
        $lojas = null;
        $max_col_span = 1;
        if ($ids = $request->get('formularios')) {
            $formularios = Formulario::whereIn('id', $ids)->get();
            $formularios->each(function (Formulario $formulario) use (&$lojas) {
                $formulario->lojas->each(function (Loja $loja) use ($formulario, &$lojas) {
                    $l = $loja->scorePorFormulario($formulario);
                    if (! isset($lojas[$loja->id])) {
                        $lojas[$loja->id] = [
                            'loja' => $loja->nome,
                            'score_total' => $loja->aproveitamento,
                            'scores_individuais' => [0 => $loja->aproveitamento],
                        ];
                    } else {
                        if ($loja->aproveitamento != 0) {
                            $lojas[$loja->id]['score_total'] += $loja->aproveitamento;
                            $lojas[$loja->id]['scores_individuais'][] = $loja->aproveitamento;
                        }
                    }
                });
            });

            foreach ($lojas as $k => $loja) {
                if (count($loja['scores_individuais']) > $max_col_span) {
                    $max_col_span = count($loja['scores_individuais']);
                }
                $lojas[$k]['score_medio'] = $loja['score_total'] / count($loja['scores_individuais']);
            }

            $lojas = collect($lojas)->sortByDesc('score_medio');
        }

        return view(self::VIEWS_PATH . 'ranking', compact('formularios_filter', 'lojas', 'max_col_span'));
    }
}
