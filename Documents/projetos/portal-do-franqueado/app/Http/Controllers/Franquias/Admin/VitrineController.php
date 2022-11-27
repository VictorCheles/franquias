<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Vitrine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VitrineController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_VITRINES_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_VITRINES_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_VITRINES_EDITAR)->only(['edit']);
        $this->middleware('acl:' . Recurso::ADM_VITRINES_DELETAR)->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = Vitrine::where(function ($q) use ($request) {
            $filter = $request->get('filter');
            if (isset($filter['status'])) {
                $q->where('status', $request->input('filter.status'));
            }
        })->orderBy('status', 'desc')->orderBy('ordem', 'asc')->paginate(10);

        return view('portal-franqueado.admin.vitrine.listar', compact('tituloPagina', 'subTituloPagina', 'lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('portal-franqueado.admin.vitrine.criar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'imagem' => 'required|image|dimensions:width=1110,height=206',
            'link' => 'url',
            'status' => 'required|boolean',
            'ordem' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                $imagem = makeFileName($request, 'imagem');
                $request->file('imagem')->move('uploads/vitrine', $imagem);
            } else {
                throw new \Exception('A imagem não é válida');
            }

            Vitrine::create([
                'titulo' => '.',
                'status' => $request->get('status'),
                'ordem' => $request->get('ordem'),
                'link' => $request->get('link'),
                'imagem' => $imagem,
            ]);

            DB::commit();

            return redirect(route('admin.vitrine.index'))->with('success', 'Vitrine criada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(url()->current())->withErrors($ex->getMessage())->withInput();
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
        //@todo nada para fazer aqui
        return redirect(route('admin.vitrine.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = Vitrine::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.vitrine.index'))->withErrors('Vitrine não encontrada');
        }

        return view('portal-franqueado.admin.vitrine.editar', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $item = Vitrine::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.vitrine.index'))->withErrors($ex->getMessage());
        }

        $validator = Validator::make($request->all(), [
            'imagem' => 'image|dimensions:width=1110,height=206',
            'link' => 'url',
            'status' => 'required|boolean',
            'ordem' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.vitrine.edit', $item->id))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                @unlink('uploads/vitrine/' . $item->imagem);
                $imagem = makeFileName($request, 'imagem');
                $item->imagem = $imagem;
                $request->file('imagem')->move('uploads/vitrine', $imagem);
            }

            $item->titulo = '.';
            $item->status = $request->get('status');
            $item->ordem = $request->get('ordem');
            $item->link = $request->get('link');
            $item->save();

            DB::commit();

            return redirect(route('admin.vitrine.index'))->with('success', 'Vitrine atualizada com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.vitrine.edit', $item->id))->withErrors($ex->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Vitrine::findOrFail($id);
            @unlink('uploads/vitrine/' . $item->imagem);
            $item->delete();
            DB::commit();

            return redirect(route('admin.vitrine.index'))->with('success', 'Vitrine deletada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.vitrine.index'))->withErrors($ex->getMessage());
        }
    }
}
