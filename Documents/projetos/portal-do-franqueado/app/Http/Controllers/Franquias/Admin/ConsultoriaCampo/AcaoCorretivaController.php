<?php

namespace App\Http\Controllers\Franquias\Admin\ConsultoriaCampo;

use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConsultoriaCampo\AcaoCorretiva;

class AcaoCorretivaController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.consultoria-campo.acoes-corretivas.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR)->only(['edit', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = AcaoCorretiva::where(function ($q) use ($request) {
            if ($loja = $request->input('filter.loja_id')) {
                $q->whereHas('visita', function ($q) use ($loja) {
                    $q->whereLojaId($loja);
                });
            }

            if ($data = $request->input('filter.data')) {
                list($inicio, $fim) = explode(' - ', $data);
                $q->whereBetween('data_correcao', [
                    Carbon::createFromFormat('d/m/Y', $inicio),
                    Carbon::createFromFormat('d/m/Y', $fim),
                ]);
            }

            if ($status = $request->input('filter.status')) {
                $q->whereStatus($status);
            }
        })->orderBy('status')->orderBy('data_correcao', 'asc')->paginate(10);

        $lojas = Loja::orderBy('nome')->get();

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'lojas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $item = AcaoCorretiva::findOrFail($id);

        return view(self::VIEWS_PATH . 'editar', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'descricao' => 'required',
            'data_correcao' => 'required|date',
            'status' => 'required',
        ]);

        $item = AcaoCorretiva::findOrFail($id);
        $item->descricao = $request->get('descricao');
        $item->data_correcao = $request->get('data_correcao');
        $item->status = $request->get('status');
        $item->save();

        return redirect()->back()->with('success', 'Ação corretiva atualizada com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
