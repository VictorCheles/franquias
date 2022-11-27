<?php

namespace App\Http\Controllers\Franquias\Admin\ConsultoriaCampo;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConsultoriaCampo\Notificacao;

class NotificacaoController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.consultoria-campo.notificacoes.';

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
                'title' => 'Criar notificação',
                'url' => route('admin.consultoria-de-campo.setup.notificacoes.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_CATEGORIA_CRIAR,
            ],
        ]);

        $lista = Notificacao::paginate(10);

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
                'url' => route('admin.consultoria-de-campo.setup.notificacoes.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_CATEGORIA_LISTAR,
            ],
        ]);

        return view(self::VIEWS_PATH . 'criar', compact('quick_actions'));
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
            'valor_un' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Notificacao::create([
                'descricao' => $request->get('descricao'),
                'valor_un' => unmaskMoney($request->get('valor_un')),
            ]);

            DB::commit();

            return redirect()->route('admin.consultoria-de-campo.setup.notificacoes.index')->with('success', 'Notificação cadastrada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup.notificacoes.create')->withErrors($ex->getMessage());
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Notificacao::findOrFail($id);

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
            'valor_un' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $notif = Notificacao::findOrFail($id);
            $notif->descricao = $request->get('descricao');
            $notif->valor_un = unmaskMoney($request->get('valor_un'));
            $notif->save();

            DB::commit();

            return redirect()->route('admin.consultoria-de-campo.setup.notificacoes.index')->with('success', 'Notificação editada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.consultoria-de-campo.setup.notificacoes.edit', $id)->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $id;
    }
}
