<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use Validator;
use App\ACL\Recurso;
use App\Models\Produto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_PRODUTOS_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::ADM_PRODUTOS_CRIAR)->only(['create']);
        $this->middleware('acl:' . Recurso::ADM_PRODUTOS_EDITAR)->only(['edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = Produto::where(function ($q) use ($request) {
            if ($nome = $request->input('filter.nome')) {
                $q->where('nome', 'ilike', "%{$nome}%");
            }
            $disponibilidade = $request->input('filter.disponivel');
            if (! is_null($disponibilidade)) {
                $q->where('disponivel', $disponibilidade);
            }
            if ($categoria = $request->input('filter.categoria')) {
                $q->where('categoria_id', $categoria);
            }
        })->orderBy('disponivel', 'desc')->orderBy('nome')->paginate(10);

        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Novo produto',
                'url' => route('admin.produto.create'),
                'icon' => 'fa fa-plus',
                'resource' => \App\ACL\Recurso::ADM_PRODUTOS_CRIAR,
            ],
        ]);

        return view('portal-franqueado.admin.produto.listar', compact('quick_actions', 'lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_actions = $this->quickActionButtons([
            'index' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.produto.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => \App\ACL\Recurso::ADM_PRODUTOS_LISTAR,
            ],
        ]);

        return view('portal-franqueado.admin.produto.criar', compact('quick_actions'));
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
            'imagem' => 'image',
            'nome' => 'required|max:100|min:5',
            'descricao' => 'required',
            'preco' => 'required',
            'disponivel' => 'required|boolean',
            'peso' => 'required|numeric',
            'categoria' => 'required|exists:categoria_produtos,id',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.produto.create'))->withErrors($validator)->withInput();
        }

        $imagem = null;
        if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
            $imagem = makeFileName($request, 'imagem');
            $request->file('imagem')->move('uploads/produto', $imagem);
        }

        DB::beginTransaction();
        try {
            Produto::create([
                'nome' => $request->get('nome'),
                'descricao' => $request->get('descricao'),
                'preco' => unmaskMoney($request->get('preco')),
                'disponivel' => $request->get('disponivel'),
                'imagem' => $imagem,
                'categoria_id' => $request->get('categoria'),
                'peso' => $request->get('peso'),
            ]);

            DB::commit();

            return redirect(route('admin.produto.index'))->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.produto.create'))->withErrors($ex->getMessage())->withInput();
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
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $item = Produto::findOrFail($id);
            $quick_actions = $this->quickActionButtons([
                'create' => [
                    'title' => 'Novo produto',
                    'url' => route('admin.produto.create'),
                    'icon' => 'fa fa-plus',
                    'resource' => \App\ACL\Recurso::ADM_PRODUTOS_CRIAR,
                ],
                'index' => [
                    'title' => 'Voltar para a lista',
                    'url' => route('admin.produto.index'),
                    'icon' => 'fa fa-arrow-left',
                    'resource' => \App\ACL\Recurso::ADM_PRODUTOS_LISTAR,
                ],
            ]);

            return view('portal-franqueado.admin.produto.editar', compact('quick_actions', 'item'));
        } catch (ModelNotFoundException $ex) {
            return redirect(route('admin.produto.index'))->withErrors($ex->getMessage());
        }
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
        try {
            $item = Produto::findOrFail($id);
        } catch (\Exception $ex) {
            return redirect(route('admin.produto.index'))->withErrors('Produto não encontrado');
        }

        $validator = Validator::make($request->all(), [
            'imagem' => 'image',
            'nome' => 'required|max:100|min:5',
            'descricao' => 'required',
            'preco' => 'required',
            'disponivel' => 'required|boolean',
            'peso' => 'required|numeric',
            'categoria' => 'required|exists:categoria_produtos,id',
        ]);

        if ($validator->fails()) {
            return redirect(route('admin.produto.edit', $item->id))->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->file('imagem')) {
                if ($request->hasFile('imagem') and $request->file('imagem')->isValid()) {
                    @unlink('uploads/produto/' . $item->imagem);
                    $item->imagem = makeFileName($request, 'imagem');
                    $request->file('imagem')->move('uploads/produto', $item->imagem);
                } else {
                    throw new \Exception('A imagem não é válida');
                }
            }

            $item->nome = $request->get('nome');
            $item->descricao = $request->get('descricao');
            $item->preco = unmaskMoney($request->get('preco'));
            $item->disponivel = $request->get('disponivel');
            $item->categoria_id = $request->get('categoria');
            $item->peso = $request->get('peso');
            $item->save();

            DB::commit();

            return redirect(route('admin.produto.index'))->with('success', 'Produto editado com sucesso!');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect(route('admin.produto.edit', $item->id))->withErrors($ex->getMessage())->withInput();
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
        //NÃO TEM
    }

    /**
     * AJAX CARAI.
     */
    public function ajaxList(Request $request)
    {
        $produtos = Produto::where(function (Builder $q) use ($request) {
            $q->where('disponivel', true);
            $q->whereHas('categoria', function ($qq) {
                $qq->where('disponivel', true);
            });
            if ($request->get('id')) {
                $q->whereNotIn('id', $request->get('id'));
            }
        })->orderBy('nome')->get();

        $produtos->each(function (Produto $item) {
            $item->preco = $item->preco_formatted;
        });

        return response()->json($produtos);
    }
}
