<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\User;
use Validator;
use App\ACL\Recurso;
use App\Models\Enquete;
use App\Models\Pergunta;
use App\Models\Comunicado;
use Illuminate\Http\Request;
use App\Models\UsuarioResposta;
use App\Http\Controllers\Controller;

class EnqueteController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_ENQUETES)->only(['indexPublic', 'responder']);

        $this->middleware('acl:' . Recurso::ADM_ENQUETES_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_ENQUETES_VER_RESULTADOS)->only(['show']);
        $this->middleware('acl:' . Recurso::ADM_ENQUETES_DELETAR)->only(['destroy']);
    }

    public function index()
    {
        $lista = Enquete::with('perguntas')->where(function ($q) {
        })->orderBy('fim', 'desc')->paginate(10);

        return view('portal-franqueado.admin.enquetes.listar', compact('lista'));
    }

    public function create()
    {
        $usuarios = User::ativo()->orderBy('nivel_acesso', 'desc')->orderBy('nome', 'asc')->get();
        $optionsUsuarios = [];
        foreach ($usuarios as $u) {
            $u->lojas->each(function ($loja) use ($u, &$optionsUsuarios) {
                $optionsUsuarios[$loja->nome][$u->id] = $u->nome . ' [' . User::$mapAcesso[$u->nivel_acesso] . ']';
            });
        }

        return view('portal-franqueado.admin.enquetes.criar', compact('optionsUsuarios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Enquete::validationRulesCreate());
        if ($validator->fails()) {
            return redirect()->route('admin.enquetes.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $enquete = Enquete::create([
                'nome' => $request->input('enquete.nome'),
                'descricao' => '...',
                'inicio' => $request->input('enquete.inicio'),
                'fim' => $request->input('enquete.fim'),
            ]);

            $enquete->destinatarios()->sync($request->get('destinatario'));

            foreach ($request->input('pergunta') as $key => $value) {
                $pergunta = Pergunta::create([
                    'enquete_id' => $enquete->id,
                    'pergunta' => $value,
                ]);
                foreach ($request->input('resposta.' . $key) as $k => $v) {
                    $pergunta->respostas()->create(['resposta' => $v]);
                }
            }
            DB::commit();

            return redirect()->route('admin.enquetes.index')->with('success', 'enquete criada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.enquetes.create')->withErrors($ex->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        try {
            $item = Enquete::findOrFail($id);

            return view('portal-franqueado.admin.enquetes.ver', compact('item'));
        } catch (\Exception $ex) {
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        try {
            $item = Enquete::findOrFail($id);
            Comunicado::where('enquete_id', $item->id)->update(['enquete_id' => null]);
            $item->delete();

            return redirect()->route('admin.enquetes.index')->with('success', 'Enquete deletada com sucesso!');
        } catch (\Exception $ex) {
            return redirect()->route('admin.enquetes.index')->withErrors($ex->getMessage());
        }
    }

    public function responder($id)
    {
        try {
            $item = Enquete::findOrFail($id);
            $usuario_respostas = Auth()->user()->enqueteRespostas($item->id)->get()->pluck('resposta_id');
            if ($usuario_respostas->count() > 0) {
                session()->flash('warning', 'Você já respondeu essa enquete!');
            }

            return view('portal-franqueado.admin.enquetes.responder', compact('item', 'usuario_respostas'));
        } catch (\Exception $ex) {
            return redirect('/')->withErrors($ex->getMessage());
        }
    }

    public function processarResposta(Request $request, $id)
    {
        $url_back = route('enquetes.index');
        try {
            $item = Enquete::findOrFail($id);
            $usuario_respostas = Auth()->user()->enqueteRespostas($item->id)->get();
            if ($usuario_respostas->count() > 0) {
                return redirect($url_back)->withErrors('Você já respondeu essa enquete');
            }
            foreach ($request->get('resposta') as $pergunta => $resposta) {
                UsuarioResposta::create([
                    'enquete_id' => $item->id,
                    'user_id' => Auth()->user()->id,
                    'pergunta_id' => $pergunta,
                    'resposta_id' => $resposta,
                ]);
            }

            return redirect($url_back)->with('success', 'Enquete respondida com sucesso!!');
        } catch (\Exception $ex) {
            return redirect($url_back)->withErrors($ex->getMessage());
        }
    }

    public function indexPublic()
    {
        $lista = Auth()->user()->enquetes()->paginate(10);

        return view('portal-franqueado.enquetes.listar', compact('lista'));
    }
}
