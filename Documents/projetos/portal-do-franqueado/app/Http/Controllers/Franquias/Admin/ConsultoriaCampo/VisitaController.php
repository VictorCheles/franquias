<?php

namespace App\Http\Controllers\Franquias\Admin\ConsultoriaCampo;

use DB;
use App\User;
use Carbon\Carbon;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Jobs\RelatorioConsultoria;
use App\Http\Controllers\Controller;
use App\Models\ConsultoriaCampo\Visita;
use App\Models\ConsultoriaCampo\Pergunta;
use App\Models\ConsultoriaCampo\Formulario;
use App\Models\ConsultoriaCampo\Notificacao;
use App\Models\ConsultoriaCampo\AcaoCorretiva;

class VisitaController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.consultoria-campo.visitas.';

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'dashboard' => [
                'title' => 'Voltar ao dashboard',
                'url' => route('admin.consultoria-de-campo'),
                'icon' => 'fa fa-dashboard',
                'resource' => \App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_DASHBOARD,
            ],
        ]);

        $minhasVisitas = Auth()->user()->visitas_pendentes->count();
        if ($minhasVisitas > 0) {
            $url = url()->current() . '?' . http_build_query(['filter' => ['user_id' => Auth()->user()->id, 'status' => Visita::STATUS_INICIADA]]);
            session()->flash('warning', "Existem {$minhasVisitas} visitas pendentes para você finalizar.<br><a href=\"{$url}\">Clique aqui</a> para vê-las");
        }

        $lista = Visita::where(function ($q) use ($request) {
            if ($formulario = $request->input('filter.formulario_id')) {
                $q->whereFormularioId($formulario);
            }
            if ($data = $request->input('filter.data')) {
                list($inicio, $fim) = explode(' - ', $data);
                $q->whereBetween('data', [
                    Carbon::createFromFormat('d/m/Y', $inicio),
                    Carbon::createFromFormat('d/m/Y', $fim),
                ]);
            }
            if ($loja = $request->input('filter.loja_id')) {
                $q->whereLojaId($loja);
            }
            if ($user = $request->input('filter.user_id')) {
                $q->whereUserId($user);
            }
            if ($status = $request->input('filter.status')) {
                $q->whereStatus($status);
            }
        })->orderBy('updated_at', 'desc')->paginate(10);
        $formularios = Formulario::whereHas('visitas', function () {
        })->orderBy('descricao')->get();
        $lojas = Loja::orderBy('nome')->get();
        $users = User::orderBy('nome')->admin()->ativo()->get();

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'formularios', 'lojas', 'users', 'quick_actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lojas = Loja::orderBy('nome', 'asc')->get();
        $users = User::admin()->ativo()->orderBy('nome')->get();
        $formularios = Formulario::orderBy('created_at', 'desc')->get();

        return view(self::VIEWS_PATH . 'criar', compact('lojas', 'users', 'formularios'));
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
            'loja_id' => 'required',
            'user_id' => 'required',
            'formulario_id' => 'required',
            'data' => 'required|date',
            'iniciar' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $visita = Visita::create([
                'loja_id' => $request->get('loja_id'),
                'user_id' => $request->get('user_id'),
                'formulario_id' => $request->get('formulario_id'),
                'data' => $request->get('data'),
                'status' => Visita::STATUS_INICIADA,
            ]);

            DB::commit();

            if ($request->get('iniciar')) {
                return redirect()->route('admin.consultoria-de-campo.visitas.show', $visita->id)->with('success', 'Visita iniciada com sucesso');
            } else {
                return redirect()->route('admin.consultoria-de-campo.visitas.index')->with('success', 'Visita cadastrada com sucesso');
            }
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.visitas.create')->withErrors($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $item = Visita::findOrFail($id);
        if ($item->user_id == Auth()->user()->id or Auth()->user()->isAdmin()) {
        } else {
            return redirect()->back()->withErrors('Você não é o avaliador dessa visita');
        }

        if ($item->status == Visita::STATUS_FINALIZADA) {
            return redirect()->route('admin.consultoria-de-campo.visitas.index')->withErrors('Essa visita já foi finalizada');
        } else {
            $item->status = Visita::STATUS_INICIADA;
        }

        $item->save();

        $notificacoes = Notificacao::orderBy('descricao')->get();
        $views_path = self::VIEWS_PATH;
        $total_perguntas = 0;
        $item->formulario->topicos->each(function ($topico) use (&$total_perguntas) {
            $total_perguntas += $topico->perguntas->count();
        });

        return view(self::VIEWS_PATH . 'ver', compact('item', 'notificacoes', 'views_path', 'total_perguntas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'resposta' => 'required|array',
            'relato_final' => 'required',
        ]);

        $resp = collect($request->get('resposta'));

        $nulls = $resp->filter(function ($r) {
            return $r == 'null';
        });

        if ($resp->count() == $nulls->count()) {
            return redirect()->back()->withErrors('Ao menos uma pergunta deve ser respondida')->withInput();
        }

        DB::beginTransaction();
        try {
            $visita = Visita::findOrFail($id);
            $visita->relato_final = $request->get('relato_final');
            $visita->status = Visita::STATUS_VALIDADA;
            $visita->user_id = Auth()->user()->id;
            $visita->save();

            foreach ($request->get('resposta') as $pergunta_id => $resposta) {
                $pergunta = Pergunta::findOrFail($pergunta_id);
                $files = [];
                if ($request->hasFile('upload_pergunta.' . $pergunta_id)) {
                    $file_name = makeFileName($request, 'upload_pergunta.' . $pergunta_id);
                    $request->file('upload_pergunta.' . $pergunta_id)->move('uploads/visitas', $file_name);
                    $files[] = $file_name;
                }

                if ($resposta == 'null') {
                    $r = null;
                } else {
                    $r = (bool) $resposta;
                }

                $visita->respostas()->create([
                    'pergunta_id' => $pergunta->id,
                    'resposta' => $r,
                    'pontuacao' => 1,
                    'fotos' => $files,
                ]);

                if (! is_null($r) and ! $r) {
                    $acao_corretiva = [
                        'descricao' => $request->input('acao_corretiva.' . $pergunta->id . '.descricao'),
                        'data_correcao' => $request->input('acao_corretiva.' . $pergunta->id . '.data_correcao'),
                    ];

                    if (empty($acao_corretiva['descricao']) or empty($acao_corretiva['data_correcao'])) {
                        throw new \Exception('Dados da ação corretiva são obrigatórios');
                    }

                    $visita->acoes_corretivas()->create(array_merge($acao_corretiva, [
                        'pergunta_id' => $pergunta_id,
                        'status' => AcaoCorretiva::STATUS_PENDENTE,
                    ]));
                }
            }

            $notificacoes_sync = [];
            if ($notificacoes = $request->get('notificacao')) {
                foreach ($notificacoes as $notificacao_id => $notificacao) {
                    $noti = Notificacao::findOrFail($notificacao_id);
                    $notificacoes_sync[$notificacao_id] = ['quantidade' => $notificacao['quantidade'], 'valor_un' => $noti->valor_un];
                    $visita->notificacoes()->sync($notificacoes_sync);
                }
            }

            DB::commit();

            $this->enviarRelatorio($request, $visita->id);

            return redirect()->route('admin.consultoria-de-campo.visitas.index')->with('success', 'Avaliação concluída com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup')->withErrors($ex->getMessage());
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
        $route = 'admin.consultoria-de-campo.visitas.index';

        DB::beginTransaction();
        try {
            $item = Visita::findOrFail($id);
            $item->acoes_corretivas()->delete();
            $item->notificacoes()->detach();
            $item->respostas()->delete();
            $item->delete();
            DB::commit();

            return redirect()->route($route)->with('success', 'Visita excluída com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route($route)->withErrors($ex->getMessage());
        }
    }

    public function detalhes($id)
    {
        $item = Visita::findOrFail($id);
        $notificacoes = Notificacao::whereNotIn('id', $item->notificacoes->pluck('id')->toArray())->get();

        return view(self::VIEWS_PATH . 'detalhes', compact('item', 'notificacoes'));
    }

    /**
     * @deprecated
     */
    public function destroyNotificacao(Request $request, $visita_id, $notificacao_id)
    {
        $visita = Visita::findOrFail($visita_id);
        $visita->notificacoes()->detach($notificacao_id);

        return redirect()->route('admin.consultoria-de-campo.visitas.detalhes', $visita->id)->with('success', 'Notificação removida com sucesso');
    }

    /**
     * @deprecated
     */
    public function createNotificacao(Request $request, $visita_id)
    {
        $this->validate($request, ['notificacao_id' => 'required', 'quantidade']);
        $visita = Visita::findOrFail($visita_id);
        $notificacao = Notificacao::findOrFail($request->get('notificacao_id'));
        $visita->notificacoes()->attach([$notificacao->id => ['quantidade' => $request->get('quantidade'), 'valor_un' => $notificacao->valor_un]]);

        return redirect()->route('admin.consultoria-de-campo.visitas.detalhes', $visita->id)->with('success', 'Notificação adicionada com sucesso');
    }

    private function enviarRelatorio($request, $id, $final = false)
    {
        $item = Visita::findOrFail($id);

        $to = collect();
        $item->loja->users->each(function ($user) use ($to) {
            $to->push($user);
        });
        $to->push($item->user);

        $to = $to->unique('id');

        $this->dispatch(new RelatorioConsultoria($to, $item));
    }
}
