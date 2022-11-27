<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\User;
use Validator;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Praca;
use App\Models\Imagem;
use App\Models\Enquete;
use App\Models\Pergunta;
use App\Models\Comunicado;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use App\Jobs\NovoComuniadoEmail;
use App\Models\EventoCalendario;
use App\Http\Controllers\Controller;
use App\Models\ComunicadoDestinatarios;

class ComunicadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_COMUNICADOS)->only(['publicoListar', 'ler']);
        $this->middleware('acl:' . Recurso::ADM_COMUNICADOS_LISTAR)->only(['listar']);
        $this->middleware('acl:' . Recurso::ADM_COMUNICADOS_CRIAR)->only(['criar']);
        $this->middleware('acl:' . Recurso::ADM_COMUNICADOS_EDITAR)->only(['editar', 'abrirEncerrarAssunto']);
    }

    public function listar(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'imagem' => [
                'title' => 'Criar imagem padrão',
                'url' => route('admin.imagem.index'),
                'icon' => 'fa fa-image',
                'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
            ],
            'create' => [
                'title' => 'Novo comunicado',
                'url' => url('/admin/comunicados/criar'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
            ],
        ]);

        $lista = Comunicado::with('destinatarios', 'setor')->where(function ($q) use ($request) {
            if ($request->input('filter')) {
                if ($titulo = $request->input('filter.titulo')) {
                    $titulo = mb_strtolower($titulo);
                    $q->where(DB::raw('lower(titulo)'), 'ilike', "%{$titulo}%");
                }
                if ($data = $request->input('filter.data')) {
                    list($inicio, $fim) = explode(' - ', $data);
                    $inicio = Carbon::createFromFormat('d/m/Y', $inicio);
                    $fim = Carbon::createFromFormat('d/m/Y', $fim);
                    $q->where('created_at', '>=', $inicio);
                    $q->where('created_at', '<=', $fim);
                }

                if ($setor = $request->input('filter.setor')) {
                    $q->where('setor_id', $setor);
                }

                if ($palavra_chave = $request->input('filter.palavra_chave')) {
                    $palavra_chave = mb_strtolower($palavra_chave);
                    $q->where(DB::raw('lower(descricao)'), 'ilike', "%{$palavra_chave}%");
                }

                /*
                 * @todo refazer
                 */
//                if($loja_id = $request->input('filter.loja_id'))
//                {
//                    $q->whereHas('destinatarios.user', function($q) use ($loja_id) {
//                        $q->whereLojaId($loja_id);
//                    });
//                }
            }
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('portal-franqueado.admin.comunicados.listar', compact('lista', 'quick_actions'));
    }

    public function criar(Request $request)
    {
        $item = null;
        if ($oringinal = $request->get('original')) {
            try {
                $item = Comunicado::findOrFail($oringinal);
            } catch (\Exception $ex) {
                return redirect(url()->current())->withErrors('Comunicado para cópia não existe');
            }
        }
        $quick_actions = $this->quickActionButtons([
            'imagem' => [
                'title' => 'Criar imagem padrão',
                'url' => route('admin.imagem.index'),
                'icon' => 'fa fa-image',
                'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
            ],
            'index' => [
                'title' => 'Voltar para lista',
                'url' => url('/admin/comunicados/listar'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_LISTAR,
            ],
        ]);

        $imagens_padrao = Imagem::orderBy('descricao')->get();
        $destinatarios = User::with('lojas', 'lojas.praca')->ativo()->orderBy('nivel_acesso', 'desc')->orderBy('nome', 'asc')->get();
        $destinatarios->each(function ($dest) {
            $dest->thumbnail = $dest->thumbnail;
            $dest->fake_name = $dest->nome_formal . '<br>[' . User::$mapAcesso[$dest->nivel_acesso] . ']';
        });
        $destinatarios_por_loja = $destinatarios->groupBy('lojas.*.nome')->toJson();
        $destinatarios_por_praca = $destinatarios->groupBy('lojas.*.praca.nome')->toJson();

        $hasImportante = Comunicado::importante()->first();

        return view('portal-franqueado.admin.comunicados.criar', compact('quick_actions', 'item', 'imagens_padrao', 'destinatarios', 'destinatarios_por_loja', 'destinatarios_por_praca', 'hasImportante'));
    }

    public function processarCriar(Request $request)
    {
        $rules = [
            'imagem' => 'required|image',
            'imagem_id' => 'required',
            'titulo' => 'required|min:5|max:50',
            'descricao' => 'required',
            'setor_id' => 'required|exists:setores,id',
            'tipo_id' => 'required',
            'destinatario.*' => 'required|exists:users,id',
            'galeria.*' => 'image',
            'anexos.*' => 'file',
            'periodo_acao' => 'date_range',
            'periodo_importancia' => 'date_range',
        ];

        if ($request->hasFile('imagem')) {
            unset($rules['imagem_id']);
        } else {
            unset($rules['imagem']);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        if ($request->get('tem-enquete')) {
            $outroValidator = Validator::make($request->all(), [
                'enquete.nome' => 'required',
                'enquete.inicio' => 'required|date',
                'enquete.fim' => 'required|date',
            ]);

            if ($outroValidator->fails()) {
                return redirect(url()->current())->withErrors($outroValidator)->withInput();
            }
        }

        if ($request->get('videos')) {
            $videos = explode("\n", $request->get('videos'));
            foreach ($videos as $v) {
                $val = Validator::make(['video' => $v], [
                   'video' => 'regex:' . '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si',
                ]);
                if ($val->fails()) {
                    return redirect(url()->current())->withErrors($val)->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            $imagem_field = 'imagem';
            if ($request->hasFile('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $imagem = makeFileName($request, 'imagem');
                    $request->file('imagem')->move('uploads/comunicados', $imagem);
                } else {
                    throw new \Exception('A imagem não é válida');
                }
            } else {
                $imagem_field .= '_id';
                $imagem = $request->get('imagem_id');
            }

            $galeria = [];
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $galeria[] = $nome;
                        $arquivo->move('uploads/comunicados', $nome);
                    }
                }
            }

            $anexos = [];
            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $anexos[] = $nome;
                        $arquivo->move('uploads/comunicados', $nome);
                    }
                }
            }

            $comunicado = Comunicado::create([
                'titulo' => $request->get('titulo'),
                'descricao' => $request->get('descricao'),
                'videos' => $request->get('videos'),
                $imagem_field => $imagem,
                'tipo_id' => $request->get('tipo_id'),
                'galeria' => $galeria,
                'anexos' => $anexos,
                'setor_id' => $request->get('setor_id'),
                'aberto_para_questionamento' => $request->get('aberto_para_questionamento'),
            ]);

            if ($periodoAcao = $request->get('periodo_acao')) {
                $event = new EventoCalendario();
                $event->periodo = $periodoAcao;
                $event->titulo = $comunicado->titulo;
                $event->relacao = Comunicado::class;
                $event->relacao_id = $comunicado->id;
                $event->save();
            }

            if ($periodoAcao = $request->get('periodo_importancia')) {
                $comunicado->periodo_importancia = $request->get('periodo_importancia');
                $comunicado->save();
            }

            if ($request->get('tem-enquete')) {
                $enquete = Enquete::create([
                    'nome' => $request->input('enquete.nome'),
                    'descricao' => 'Enquete do comunicado ' . $comunicado->titulo,
                    'inicio' => $request->input('enquete.inicio'),
                    'fim' => $request->input('enquete.fim'),
                ]);

                $enquete->destinatarios()->sync($request->get('destinatario'));

                $pergunta = Pergunta::create([
                    'enquete_id' => $enquete->id,
                    'pergunta' => $request->input('enquete.nome'),
                ]);
                collect(['Sim', 'Não'])->each(function ($resposta) use ($pergunta) {
                    $pergunta->respostas()->create(['resposta' => $resposta]);
                });
                $comunicado->enquete_id = $enquete->id;
                $comunicado->save();
            }

            foreach ($request->get('destinatario') as $dest) {
                ComunicadoDestinatarios::create([
                    'user_id' => $dest,
                    'comunicado_id' => $comunicado->id,
                ]);

                Notificacao::create([
                    'destinatario' => $dest,
                    'mensagem' => 'Um novo comunicado foi cadastrado',
                    'tipo' => Notificacao::TIPO_COMUNICADO,
                    'atributos' => [
                        'comunicado_id' => $comunicado->id,
                        'comunicado_titulo' => $comunicado->titulo,
                    ],
                ]);
            }

            $this->dispatch(new NovoComuniadoEmail(User::whereIn('id', $request->get('destinatario'))->get(), $comunicado));

            DB::commit();

            return redirect('/admin/comunicados/listar')->with('success', 'Comunicado cadastrado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
        }
    }

    public function editar(Request $request, $id)
    {
        try {
            $item = Comunicado::findOrFail($id);

            $hasImportante = Comunicado::orWhere(function ($q) {
                $q->orWhere('inicio_importancia', '<', Carbon::now());
                $q->orWhere('fim_importancia', '>', Carbon::now());
            })->get()->first();

            if ($hasImportante and $item->id == $hasImportante->id) {
                $hasImportante = false;
            }

            $quick_actions = $this->quickActionButtons([
                'imagem' => [
                    'title' => 'Criar imagem padrão',
                    'url' => route('admin.imagem.index'),
                    'icon' => 'fa fa-image',
                    'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
                ],
                'index' => [
                    'title' => 'Voltar para lista',
                    'url' => url('/admin/comunicados/listar'),
                    'icon' => 'fa fa-arrow-left',
                    'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_LISTAR,
                ],
                'create' => [
                    'title' => 'Novo comunicado',
                    'url' => url('/admin/comunicados/criar'),
                    'icon' => 'fa fa-plus',
                    'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
                ],
            ]);

            $imagens_padrao = Imagem::orderBy('descricao')->get();
            $destinatarios = User::with(['lojas', 'lojas.praca'])->where('status', User::STATUS_ATIVO)->orderBy('nivel_acesso', 'desc')->orderBy('nome', 'asc')->get();
            $destinatarios->each(function ($dest) {
                $dest->thumbnail = $dest->thumbnail;
                $dest->fake_name = $dest->nome_formal . '<br>[' . User::$mapAcesso[$dest->nivel_acesso] . ']';
            });
            $destinatarios_por_loja = $destinatarios->groupBy('lojas.*.nome')->toJson();
            $destinatarios_por_praca = $destinatarios->groupBy('lojas.*.praca.nome')->toJson();

            return view('portal-franqueado.admin.comunicados.editar', compact('item', 'quick_actions', 'imagens_padrao', 'destinatarios', 'destinatarios_por_loja', 'destinatarios_por_praca', 'hasImportante'));
        } catch (\Exception $ex) {
            return redirect('/admin/comunicados/listar')->withErrors('Comunicado não encontrado '. $ex->getMessage());
        }
    }

    public function processarEditar(Request $request, $id)
    {
        try {
            $item = Comunicado::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect('/admin/comunicados/listar')->withErrors('Comunicado não encontrado');
        }

        $rules = [
            'titulo' => 'required|min:5|max:50',
            'descricao' => 'required',
            'setor_id' => 'required|exists:setores,id',
            'destinatario.*' => 'required|exists:users,id',
            'tipo_id' => 'required',
            'galeria.*' => 'image',
        ];

        if ($request->hasFile('imagem')) {
            $rule['imagem'] = 'required|image';
        } else {
            $rule['imagem_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        if ($request->get('videos')) {
            $videos = explode("\n", $request->get('videos'));
            foreach ($videos as $v) {
                $val = Validator::make(['video' => $v], [
                    'video' => 'regex:' . '#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si',
                ]);
                if ($val->fails()) {
                    return redirect(url()->current())->withErrors($val)->withInput();
                }
            }
        }

        DB::beginTransaction();
        try {
            if ($request->file('imagem')) {
                if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                    @unlink('uploads/comunicados/' . $item->imagem);
                    $item->imagem = $request->file('imagem')->getClientOriginalName();
                    $item->imagem_id = null;
                    $request->file('imagem')->move('uploads/comunicados', $item->imagem);
                } else {
                    throw new \Exception('A imagem não é válida');
                }
            } elseif ($imagem_id = $request->get('imagem_id')) {
                @unlink('uploads/comunicados/' . $item->imagem);
                $item->imagem = null;
                $item->imagem_id = $imagem_id;
            }

            $galeria = $item->galeria;
            if ($request->hasFile('galeria')) {
                foreach ($request->file('galeria') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $galeria[] = $nome;
                        $arquivo->move('uploads/comunicados', $nome);
                    }
                }
            }

            $anexos = $item->anexos;
            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $anexos[] = $nome;
                        $arquivo->move('uploads/comunicados', $nome);
                    }
                }
            }

            $item->titulo = $request->get('titulo');
            $item->videos = $request->get('videos');
            $item->descricao = $request->get('descricao');
            $item->setor_id = $request->get('setor_id');
            $item->tipo_id = $request->get('tipo_id');
            $item->aberto_para_questionamento = $request->get('aberto_para_questionamento');
            $item->galeria = $galeria;
            $item->anexos = $anexos;

            if ($periodoAcao = $request->get('periodo_importancia')) {
                $item->periodo_importancia = $request->get('periodo_importancia');
            }

            $item->save();

            $origDestinatarios = $item->destinatarios()->lists('user_id');
            $requestDestinatarios = collect($request->get('destinatario'));
            foreach ($requestDestinatarios->diff($origDestinatarios) as $dest) {
                ComunicadoDestinatarios::create([
                    'user_id' => $dest,
                    'comunicado_id' => $item->id,
                ]);

                Notificacao::create([
                    'destinatario' => $dest,
                    'mensagem' => 'Um novo comunicado foi cadastrado',
                    'tipo' => Notificacao::TIPO_COMUNICADO,
                    'atributos' => [
                        'comunicado_id' => $item->id,
                        'comunicado_titulo' => $item->titulo,
                    ],
                ]);
            }
            DB::commit();

            return redirect('/admin/comunicados/listar')->with('success', 'Comunicado editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
        }
    }

    public function ler(Request $request, $id)
    {
        try {
            $item = Comunicado::findOrFail($id);
            $dest = $item->destinatarios()->where('user_id', Auth()->user()->id)->first();

            if ($dest) {
                $dest->status = true;
                $dest->save();
            } else {
                if (Auth()->user()->isAdmin()) {
                } else {
                    throw new \Exception('');
                }
            }

            $videos = collect();
            if (! empty($item->videos)) {
                $videos = collect(explode("\n", $item->videos));
            }

            $notificacao = Auth()->user()->notificacoesComunicado()
                ->where('atributos->comunicado_id', $item->id)->first();

            if ($notificacao) {
                $notificacao->status = true;
                $notificacao->save();
            }

            $usuario_respostas = false;
            if ($item->enquete) {
                $usuario_respostas = Auth()->user()->enqueteRespostas($item->enquete->id)->get()->pluck('resposta_id');
            }

            if (Auth()->user()->isAdmin()) {
                $questionamentos = $item->questionamentos->groupBy('user_id');
            } else {
                $questionamentos = $item->questionamentos()->whereUserId(Auth()->user()->id)->get();
            }

            $lidos = collect();
            $nao_lidos = collect();
            $item->destinatarios()->orderBy('status')->orderBy('updated_at')->get()->each(function ($item) use ($lidos, $nao_lidos) {
                if ($item->status) {
                    $lidos->push($item);
                } else {
                    $nao_lidos->push($item);
                }
            });

            $porcentagem_lido = ($lidos->count() / $item->destinatarios->count()) * 100;

            return view('portal-franqueado.comunicados.ler', compact('item', 'videos', 'usuario_respostas', 'lidos', 'nao_lidos', 'questionamentos', 'porcentagem_lido'));
        } catch (\Exception $ex) {
            return redirect('/comunicados/listar/')->withErrors('Comunicado não encontrado' . $ex->getMessage());
        }
    }

    public function publicoListar(Request $request)
    {
        if (Auth()->user()->isAdmin()) {
            $lista = Comunicado::select('*');
        } else {
            $lista = Auth()->user()->comunicados();
        }

        $lista = $lista->with('setor')->where(function ($q) use ($request) {
            if ($nome = $request->input('filter.nome')) {
                $q->orWhere('comunicados.titulo', 'ilike', "%{$nome}%");
                $q->orWhere('comunicados.descricao', 'ilike', "%{$nome}%");
            }
            if ($setor = $request->input('filter.setor_id')) {
                $q->orWhere('setor_id', $setor);
            }
        })->orderBy('comunicados.created_at', 'desc')->paginate(10);

        return view('portal-franqueado.comunicados.listar', compact('tituloPagina', 'subTituloPagina', 'lista'));
    }

    public function processarDeletar($id)
    {
        DB::beginTransaction();
        try {
            $comunicado = Comunicado::findOrFail($id);
            @unlink('uploads/comunicados/' . $comunicado->imagem);
            ComunicadoDestinatarios::where('comunicado_id', $id)->delete();
            Notificacao::where('atributos->comunicado_id', $id)->delete();
            if (! empty($comunicado->galeria)) {
                foreach ($comunicado->galeria as $img) {
                    @unlink('uploads/comunicados/' . $img);
                }
            }
            $comunicado->delete();
            DB::commit();

            return redirect(url('admin/comunicados/listar'))->with('success', 'Comunicado deletado com sucesso!!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url('admin/comunicados/listar'))->withErrors($ex->getMessage());
        }
    }

    public function deletarImagem(Request $request)
    {
        try {
            $stack = $request->get('stack');
            $item = Comunicado::findOrFail($request->get('id'));
            if ($item->$stack) {
                $key = collect($item->$stack)->search($request->get('file'));
                $imagens = $item->$stack;
                unset($imagens[$key]);
                $item->$stack = $imagens;
                @unlink('uploads/comunicados/' . $request->get('file'));
                $item->save();

                return response()->json(['success' => true]);
            }
        } catch (\Exception $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function abrirEncerrarAssunto(Request $request, $comunicado_id)
    {
        DB::beginTransaction();
        try {
            $item = Comunicado::findOrFail($comunicado_id);
            $item->aberto_para_questionamento = ! $item->aberto_para_questionamento;
            $item->save();
            $message = 'Assunto encerrado';
            if ($item->aberto_para_questionamento) {
                $message = 'Comunicado aberto para discussão';
            }
            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
