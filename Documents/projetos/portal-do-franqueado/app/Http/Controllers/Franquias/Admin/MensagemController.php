<?php

namespace App\Http\Controllers\Franquias\Admin;

use Auth;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Mensagens\Mensagem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MensagemController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.mensagens.';

    //TODO: Criar as views e fazer a implementação.
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = Mensagem::where('to_id', Auth()->user()->id)
            ->where(function ($q) use ($request) {
                $q->where('folder', Mensagem::FOLDER_SENT);
                if ($from_id = $request->input('filter.from_id')) {
                    $q->whereFromId($from_id);
                }

                if ($palavra_chave = $request->input('filter.palavra_chave')) {
                    $q->where(function ($q) use ($palavra_chave) {
                        $q->orWhereRaw('lower(subject) ilike lower(?)', ["%{$palavra_chave}%"]);
                        $q->orWhereRaw('lower(text) ilike lower(?)', ["%{$palavra_chave}%"]);
                    });
                }
            })
            ->orderBy('read_in', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate();
        $titulo = 'Recebidas';
        $quem = 'from';

        $ids = Mensagem::distinct()->select('from_id')->where('to_id', $request->user()->id)->get()->map(function ($u) {
            return $u->from_id;
        });

        $users = User::whereIn('id', $ids)->get()->pluck('nome', 'id');

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'titulo', 'quem', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $users = User::ativo()->semMim()->orderBy('nome')->get()->each(function (User $user) {
            $user->nome = $user->nome . ' | ' . $user->lojas->pluck('nome')->implode(' | ');
        });

        $response = null;
        if ($id) {
            $response = Mensagem::findOrFail($id);
        }
        $titulo = '';

        return view(self::VIEWS_PATH . 'criar', compact('users', 'response', 'titulo', 'default_user'));
    }

    public function enviadas(Request $request)
    {
        $lista = Mensagem::where('from_id', Auth()->user()->id)
            ->where(function ($q) use ($request) {
                $q->where('folder', Mensagem::FOLDER_SENT);
                if ($from_id = $request->input('filter.from_id')) {
                    $q->whereToId($from_id);
                }

                if ($palavra_chave = $request->input('filter.palavra_chave')) {
                    $q->where(function ($q) use ($palavra_chave) {
                        $q->orWhereRaw('lower(subject) ilike lower(?)', ["%{$palavra_chave}%"]);
                        $q->orWhereRaw('lower(text) ilike lower(?)', ["%{$palavra_chave}%"]);
                    });
                }
            })
            ->orderBy('read_in', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate();
        $titulo = 'Enviadas';
        $quem = 'to';

        $ids = Mensagem::distinct()->select('to_id')->where('from_id', $request->user()->id)->get()->map(function ($u) {
            return $u->to_id;
        });

        $users = User::whereIn('id', $ids)->get()->pluck('nome', 'id');

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'titulo', 'quem', 'users'));
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
            'subject' => ['required', 'string', 'max:255'],
            'text' => ['required'],
            'folder' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $mensagem = new Mensagem();
            $mensagem->fill([
                'subject' => $request->get('subject'),
                'text' => $request->get('text'),
                'folder' => $request->get('folder'),
            ]);

            empty($request->get('to_id')) ?: $mensagem->to_id = $request->get('to_id');
            empty($request->get('response_id')) ?: $mensagem->message_response_id = $request->get('response_id');

            $mensagem->from_id = Auth()->user()->id;
            $anexos = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $arquivo) {
                    if ($arquivo) {
                        $ext = $arquivo->getClientOriginalExtension();
                        $nome = str_slug(str_replace('.' . $ext, '', $arquivo->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
                        $anexos[] = $nome;
                        $arquivo->move('uploads/mensagens', $nome);
                    }
                }
            }

            $mensagem->attachments = $anexos;
            $mensagem->save();

            DB::commit();

            return redirect()->route('admin.mensagens')->with('success', 'Mensagem salva com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.mensagens')->withErrors($ex->getMessage());
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
        $item = Mensagem::findOrFail($id);

        if ($item->read_in == null and \Auth::user()->id != $item->from_id) {
            $item->read_in = Carbon::now();
            $item->save();
        }
        $views_path = self::VIEWS_PATH;
        $titulo = 'Entrada';

        return view(self::VIEWS_PATH . 'ver', compact('item', 'views_path', 'titulo'));
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
            //
        ]);

        DB::beginTransaction();
        try {
//            $mensagem = Mensagem::findOrFail($id);
//            $mensagem->save();

            DB::commit();

            return redirect()->route('admin.mensagens.index')->with('success', 'Mensagem editada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.mensagens.edit', $id)->withErrors($ex->getMessage());
        }
    }
}
