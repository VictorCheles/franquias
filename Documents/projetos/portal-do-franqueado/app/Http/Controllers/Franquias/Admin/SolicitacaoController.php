<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\User;
use Validator;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Setor;
use App\Models\Notificacao;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use App\Jobs\NovaSolicitacaoEmail;
use App\Http\Controllers\Controller;
use App\Models\SolicitacaoHistorico;
use App\Jobs\FeedbackSolicitacaoEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SolicitacaoController extends Controller
{
    public function __construct()
    {
        if (str_is('*admin*', url()->current())) {
            $this->middleware('acl:' . Recurso::ADM_SOLICITACOES_LISTAR)->only(['index']);
            $this->middleware('acl:' . Recurso::ADM_SOLICITACOES_CRIAR)->only(['create']);
            $this->middleware('acl:' . Recurso::ADM_SOLICITACOES_DELETAR)->only(['destroy']);
        } else {
            $this->middleware('acl:' . Recurso::PUB_SOLICITACOES)->only(['index', 'create']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $indicadores = null;
        $solicitantes = null;
        $ultimas = null;

        $lista = Solicitacao::with('historico')->where(function ($q) use ($request, &$indicadores, &$solicitantes) {
            if ($request->user()->isAdmin()) {
                $where = 'orWhere';
                $whereIn = 'orWhereIn';
                if ($user_id = $request->input('filter.user_id')) {
                    $where = 'where';
                    $whereIn = 'whereIn';
                }
                $q->where(function ($q) use ($request, $where, $whereIn) {
                    $q->$whereIn('setor_id', $request->user()->setores->pluck('id')->toArray());
                    $q->$where('user_id', $request->input('filter.user_id') ? $request->input('filter.user_id') : $request->user()->id);
                });

                if ($setor_id = $request->input('filter.setor_id')) {
                    $q->whereSetorId($setor_id);
                }

                if ($status = $request->input('filter.status')) {
                    $q->whereStatus($status);
                }

                if ($palavra_chave = $request->input('filter.palavra_chave')) {
                    if (strlen($palavra_chave) == 4 and (strpos($palavra_chave, '#') == 0)) {
                        $q->whereHas('setor', function ($q) use ($palavra_chave) {
                            $tag = ltrim($palavra_chave, '#');
                            $q->whereTag(strtoupper($tag));
                        });
                    } else {
                        $q->where(function ($q) use ($palavra_chave) {
                            $q->orWhereRaw('lower(titulo) ilike lower(?)', ["%{$palavra_chave}%"]);
                            $q->orWhereRaw('lower(descricao) ilike lower(?)', ["%{$palavra_chave}%"]);
                            $q->orWhereHas('user', function ($q) use ($palavra_chave) {
                                $q->orWhereRaw('lower(nome) ilike lower(?)', ["%{$palavra_chave}%"]);
                            });
                            $q->orWhereHas('historico', function ($q) use ($palavra_chave) {
                                $q->orWhereRaw('lower(observacoes) ilike lower(?)', ["%{$palavra_chave}%"]);
                            });
                        });
                    }
                }

                $dumpData = Solicitacao::with('user')->select('id', 'status', 'user_id')->where(function ($q) use ($request) {
                    $q->where(function ($q) use ($request) {
                        $q->orWhereIn('setor_id', $request->user()->setores->pluck('id')->toArray());
                        $q->orWhere('user_id', $request->user()->id);
                    });
                })->get();

                $solicitantes = $dumpData->map(function ($item) {
                    return $item->user;
                });
                $solicitantes = $solicitantes->unique('id')->sortBy('nome');

                $indicadores = $dumpData->sortBy('status')->groupBy('status');
            } else {
                $dumpData = Solicitacao::with('user')->select('id', 'status', 'user_id')->where(function ($q) use ($request) {
                    $q->where(function ($q) use ($request) {
                        $q->whereUserId($request->user()->id);
                    });
                })->get();

                $indicadores = $dumpData->sortBy('status')->groupBy('status');

                $q->whereUserId($request->user()->id);
                if ($status = $request->input('filter.status')) {
                    $q->whereStatus($status);
                }

                if ($palavra_chave = $request->input('filter.palavra_chave')) {
                    $q->where(function ($q) use ($palavra_chave) {
                        $q->orWhereRaw('lower(titulo) ilike lower(?)', ["%{$palavra_chave}%"]);
                        $q->orWhereRaw('lower(descricao) ilike lower(?)', ["%{$palavra_chave}%"]);
                        $q->orWhereHas('historico', function ($q) use ($palavra_chave) {
                            $q->orWhereRaw('lower(observacoes) ilike lower(?)', ["%{$palavra_chave}%"]);
                        });
                    });
                }
            }
        })->orderBy('created_at', 'desc')->paginate(10);

        if ($request->user()->isAdmin()) {
            $ultimas = Solicitacao::from('solicitacoes as s')
                ->selectRaw('*, (select created_at from solicitacao_historico sh where sh.solicitacao_id = s.id order by created_at desc limit 1) as h_created_at')
                ->whereRaw('(select created_at from solicitacao_historico sh where sh.solicitacao_id = s.id order by created_at desc limit 1) is not null')
                ->where(function ($q) use ($request) {
                    $q->where(function ($q) use ($request) {
                        $q->orWhereIn('setor_id', $request->user()->setores->pluck('id')->toArray());
                        $q->orWhere('user_id', $request->input('filter.user_id') ? $request->input('filter.user_id') : $request->user()->id);
                    });
                })->orderByRaw('updated_at desc, h_created_at desc')->take($request->input('ultimas.limit') ?: 5)->get();
        }

        if ($request->ajax()) {
            return view('portal-franqueado.admin.solicitacao.listar_ajax', [
                'lista' => $lista,
                'auth' => Auth()->user(),
            ]);
        }

        return view('portal-franqueado.admin.solicitacao.listar', compact('lista', 'indicadores', 'solicitantes', 'ultimas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth()->user()->isAdmin()) {
            $setores = Setor::orderBy('nome', 'asc')->lists('nome', 'id');
        } else {
            $setores = Setor::where('interno', false)->orderBy('nome', 'asc')->lists('nome', 'id');
        }

        return view('portal-franqueado.admin.solicitacao.criar', compact('setores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'setor' => 'required|exists:setores,id',
            'titulo' => 'required',
            'descricao' => 'required',
            'anexos.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect(route('solicitacao.create'))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $anexos = null;
            if (! is_null($request->file('anexos'))) {
                foreach ($request->file('anexos') as $a) {
                    if (! is_null($a)) {
                        if (! $a->isValid()) {
                            throw new \Exception('O arquivo ' . $a->getClientOriginalName());
                        }
                        $fileName = str_slug(str_replace($a->getClientOriginalExtension(), '', $a->getClientOriginalName()) . '-' . microtime()) . '.' . $a->getClientOriginalExtension();
                        $a->move('uploads/solicitacao', $fileName);
                        $anexos[] = $fileName;
                    }
                }
            }

            $solicitacao = Solicitacao::create([
                'titulo' => $request->get('titulo'),
                'descricao' => $request->get('descricao'),
                'status' => Solicitacao::STATUS_NOVA,
                'user_id' => Auth()->user()->id,
                'setor_id' => $request->get('setor'),
                'anexos' => $anexos,
            ]);

            $setor = Setor::findOrFail($request->get('setor'));

            if ($setor->responsaveis->count() > 0) {
                $setor->responsaveis->each(function (User $user) use ($solicitacao) {
                    Notificacao::create([
                        'destinatario' => $user->id,
                        'mensagem' => 'Uma nova solicitação foi criada',
                        'tipo' => Notificacao::TIPO_SOLICITACAO,
                        'atributos' => [
                            'solicitacao_id' => $solicitacao->id,
                            'solicitacao_titulo' => $solicitacao->titulo,
                        ],
                    ]);
                });
            }

            $this->dispatch(new NovaSolicitacaoEmail($setor->responsaveis, $solicitacao));
            DB::commit();

            return redirect(route('solicitacao.index'))->with('success', 'Solicitação criada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('solicitacao.create'))->withErrors($ex->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $item = Solicitacao::findOrFail($id);
            if (! Auth()->user()->isAdmin() and $item->user_id != Auth()->user()->id) {
                throw new ModelNotFoundException();
            }
        } catch (ModelNotFoundException $ex) {
            return redirect(route('solicitacao.index'))->withErrors('Solicitação não encontrada');
        }

        Notificacao::where('atributos->solicitacao_id', '=', $item->id)->where('destinatario', '=', Auth()->user()->id)->update(['status' => true]);

        $setor = $item->setor()->first();
        $historico = $item->historico()->orderBy('created_at', 'desc')->get();
        $historicoGrouped = false;
        if ($historico->count() > 0) {
            foreach ($historico as $h) {
                $historicoGrouped[$h->created_at->format('Y-m-d')][] = $h;
            }
        }
        $solicitante = $item->user()->first();

        return view('portal-franqueado.admin.solicitacao.ver', compact('tituloPagina', 'subTituloPagina', 'item', 'setor', 'solicitante', 'historico', 'historicoGrouped'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //@todo YOU NO NOTHING JOHN SNOW
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $item = Solicitacao::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect(route('solicitacao.index'))->withErrors('Solicitação não encontrada');
        }

        $rules = [
            'observacoes' => 'required',
            'status' => 'required',
            'prazo' => 'required|date',
            'anexos.*' => 'file',
        ];

        if (! Auth()->user()->isAdmin()) {
            unset($rules['prazo']);
            //unset($rules['status']);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect(route('solicitacao.show', $item->id))->withErrors($validator);
        }

        $extra = '';

        if (is_null($item->prazo) and $request->get('prazo')) {
            $extra .= '<p><i>Prazo de conclusão definido para: <b>' . Carbon::parse($request->get('prazo'))->format('d/m/Y') . '</b></i></p>';
        }

        if ($request->get('prazo') and ! is_null($item->prazo)) {
            if (! Carbon::parse($request->get('prazo'))->eq(Carbon::parse($item->prazo->format('Y-m-d')))) {
                $extra .= '<p><i>Prazo de conclusão modificado de <b>' . $item->prazo->format('d/m/Y') . '</b> para: <b>' . Carbon::parse($request->get('prazo'))->format('d/m/Y') . '</b></i></p>';
            }
        }

        $novo_setor_id = null;
        $mudou_setor = false;
        if ($novo_setor_id = $request->get('setor_id')) {
            if ($novo_setor_id != $item->setor_id) {
                $antigo = $item->setor->nome;
                $novo = Setor::find($novo_setor_id);
                $extra .= "<p><i>Solicitação encaminhada do setor {$antigo} para o setor {$novo->nome}</i></p>";
                $item->setor_id = $novo_setor_id;
                $mudou_setor = true;
            }
        }

        DB::beginTransaction();
        try {
            $anexos = null;
            if (! is_null($request->file('anexos'))) {
                foreach ($request->file('anexos') as $a) {
                    if (! is_null($a)) {
                        if (! $a->isValid()) {
                            throw new \Exception('O arquivo ' . $a->getClientOriginalName());
                        }
                        $fileName = str_slug(str_replace($a->getClientOriginalExtension(), '', $a->getClientOriginalName()) . '-' . microtime()) . '.' . $a->getClientOriginalExtension();
                        $extra .= '<p><i>O arquivo <a target="_blank" href="'. asset('uploads/solicitacao/' . $fileName) .'">' . $fileName . '</a> foi adicionado</i></p>';
                        $a->move('uploads/solicitacao', $fileName);
                        $anexos[] = $fileName;
                    }
                }

                if (! is_null($anexos)) {
                    if (! is_null($item->anexos)) {
                        $item->anexos = array_merge($item->anexos, $anexos);
                    } else {
                        $item->anexos = $anexos;
                    }
                }
            }

            if ($request->get('status')) {
                $status_atual = $request->get('status');
            } else {
                $status_atual = $item->status;
            }

            SolicitacaoHistorico::create([
                'solicitacao_id' => $item->id,
                'observacoes' => $request->get('observacoes') . $extra,
                'status_anterior' => $item->status,
                'status_atual' => $status_atual,
                'user_id' => Auth()->user()->id,
            ]);

            $item->status = $status_atual;
            if ($request->get('prazo')) {
                $item->prazo = $request->get('prazo');
            }
            $item->save();

            $dests = collect();
            //se o remetente enviou um feedback, entao os destinaarios vao ser os responsaveis pelo setor

            if (is_null($novo_setor_id)) {
                if (Auth()->user()->id == $item->user_id) {
                    $item->setor->responsaveis->each(function (User $u) use (&$dests) {
                        $dests->push($u);
                    });
                } else {
                    $dests->push($item->user);
                }
            } else {
                $item->setor->responsaveis->each(function (User $u) use (&$dests) {
                    $dests->push($u);
                });
                $dests->push($item->user);
            }

            if ($dests->count() > 0) {
                $dests->each(function (User $i) use ($item, $mudou_setor) {
                    $msg = 'Um feedback foi feito em uma solicitação';
                    if ($mudou_setor) {
                        $msg .= ', e foi movida de setor.';
                    }

                    Notificacao::create([
                        'destinatario' => $i->id,
                        'mensagem' => $msg,
                        'tipo' => Notificacao::TIPO_SOLICITACAO,
                        'atributos' => [
                            'solicitacao_id' => $item->id,
                            'solicitacao_titulo' => 'Atualização em (' . $item->titulo . ')',
                        ],
                    ]);
                });
            }

            $this->dispatch(new FeedbackSolicitacaoEmail($dests, $item, Auth()->user()));
            DB::commit();

            return redirect(route('solicitacao.show', $item->id))->with('success', 'Solicitação atualizada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('solicitacao.show', $item->id))->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $solicitacao = Solicitacao::findOrFail($id);
            if (! is_null($solicitacao->anexos)) {
                foreach ($solicitacao->anexos as $anexo) {
                    @unlink('uploads/solicitacao/' . $anexo);
                }
            }
            Notificacao::where('atributos->solicitacao_id', $id)->delete();
            SolicitacaoHistorico::where('solicitacao_id', $id)->delete();
            $solicitacao->delete();
            DB::commit();

            return redirect(route('admin.solicitacao.index'))->with('success', 'Solicitação deletada com sucesso!!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.solicitacao.index'))->withErrors($ex->getMessage());
        }
    }
}
