<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\Models\Imagem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImagemController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.imagem.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $quick_actions = $this->quickActionButtons([
            'imagem' => [
                'title' => 'Criar imagem padrão',
                'url' => route('admin.imagem.create'),
                'icon' => 'fa fa-image',
                'resource' => \App\ACL\Recurso::ADM_COMUNICADOS_CRIAR,
            ],
        ]);

        $lista = Imagem::orderBy('id', 'desc')->paginate(10);

        return view(self::VIEWS_PATH . 'listar', compact('lista', 'quick_actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(self::VIEWS_PATH . 'criar');
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
            'imagem' => 'image|required',
            'descricao' => 'required|max:255',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                $imagem = makeFileName($request, 'imagem');
                $request->file('imagem')->move('uploads/imagem', $imagem);
            } else {
                throw new \Exception('A imagem não é válida');
            }

            Imagem::create([
                'url' => $imagem,
                'descricao' => $request->get('descricao'),
            ]);

            DB::commit();

            return redirect()->route('admin.imagem.index')->with('success', 'Imagem criada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.imagem.create')->withErrors($ex->getMessage())->withInput();
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
        return __METHOD__;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return __METHOD__;
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
        return __METHOD__;
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
            $item = Imagem::findOrFail($id);
            @unlink('uploads/imagem/' . $item->url);
            $item->delete();
            DB::commit();

            return redirect()->route('admin.imagem.index')->with('success', 'Imagem removida com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.imagem.index')->withErrors($ex->getMessage());
        }
    }
}
