<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\ClienteLoja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClienteLojaEstabelecimento;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClienteLojaController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.cliente-loja.cliente.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_CLIENTE_LOJA_LISTAR)->only(['index']);
        $this->middleware('acl:' . Recurso::PUB_CLIENTE_LOJA_CRIAR)->only(['create', 'store']);
        $this->middleware('acl:' . Recurso::PUB_CLIENTE_LOJA_EDITAR)->only(['edit', 'update']);
        $this->middleware('acl:' . Recurso::PUB_CLIENTE_LOJA_DELETAR)->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lista = ClienteLoja::orderBy('nome');

        if (! Auth()->user()->isAdmin()) {
            $lista = $lista->where('loja_id', Auth()->user()->loja_id);
        } else {
            if ($request->input('filter') and $request->input('filter.loja')) {
                $lista->where('loja_id', $request->input('filter.loja'));
            }
        }

        $lista->where(function ($q) use ($request) {
            if ($request->input('filter')) {
                if ($nome = $request->input('filter.nome')) {
                    $nome = mb_strtolower($nome);
                    $q->where(DB::raw('lower(nome)'), 'ilike', "%{$nome}%");
                }

                if ($email = $request->input('filter.email')) {
                    $email = mb_strtolower($email);
                    $q->where(DB::raw('lower(email)'), 'ilike', "%{$email}%");
                }

                if ($telefone = $request->input('filter.telefone')) {
                    $q->where('telefone', 'ilike', "%{$telefone}%");
                }
                if ($estabelecimento = $request->input('filter.estabelecimento')) {
                    $q->where('estabelecimento_id', $estabelecimento);
                }
            }
        });

        $lista = $lista->paginate(10);

        return view(self::VIEWS_PATH . 'listar', compact('lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estabelecimentos = ClienteLojaEstabelecimento::orderBy('nome')->pluck('nome', 'id')->toArray();

        return view(self::VIEWS_PATH . 'criar', compact('estabelecimentos'));
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
            'nome' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'estabelecimento_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $loja_id = Auth()->user()->loja_id;
            ClienteLoja::create([
                'nome' => $request->get('nome'),
                'email' => $request->get('email'),
                'telefone' => $request->get('telefone'),
                'estabelecimento_id' => $request->get('estabelecimento_id'),
                'loja_id' => $loja_id,
                'user_id' => Auth()->user()->id,
            ]);

            DB::commit();

            return redirect()->route('clientes_loja.index')->with('success', 'Cliente cadastrado com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('clientes_loja.create')->withErrors($exception->getMessage());
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
            $item = ClienteLoja::findOrFail($id);
            $estabelecimentos = ClienteLojaEstabelecimento::orderBy('nome')->pluck('nome', 'id')->toArray();

            return view(self::VIEWS_PATH . 'editar', compact('item', 'estabelecimentos'));
        } catch (\Exception $ex) {
            return redirect()->route('clientes_loja.index')->withErrors($ex->getMessage());
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
            $item = ClienteLoja::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('clientes_loja.index')->withErrors($ex->getMessage());
        }

        $this->validate($request, [
            'nome' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'estabelecimento_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            collect($item->getFillable())->each(function ($col) use (&$item, $request) {
                if ($request->has($col)) {
                    $item->$col = $request->get($col);
                }
            });

            $item->save();
            DB::commit();

            return redirect()->route('clientes_loja.index')->with('success', 'Cliente editado com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('clientes_loja.update', $item->id)->withErrors($ex->getMessage());
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
        try {
            $item = ClienteLoja::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('clientes_loja.index')->withErrors($ex->getMessage());
        }

        DB::beginTransaction();
        try {
            $item->delete();
            DB::commit();

            return redirect()->route('clientes_loja.index')->with('success', 'Cliente removido com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('clientes_loja.index')->withErrors($ex->getMessage());
        }
    }

    public function exportarExcel(Request $request)
    {
        $lista = ClienteLoja::orderBy('nome');

        $data = [];

        if (! Auth()->user()->isAdmin()) {
            $lista = $lista->where('loja_id', Auth()->user()->loja_id);
        } else {
            if ($loja = $request->get('loja')) {
                $data[] = ['Filtro de loja aplicado: ', Loja::find($loja)->nome];
                $lista->where('loja_id', $loja);
            }
        }

        $lista->where(function ($q) use ($request, &$data) {
            if ($nome = $request->get('nome')) {
                $data[] = ['Filtro de nome aplicado: ', $nome];
                $nome = mb_strtolower($nome);
                $q->where(DB::raw('lower(nome)'), 'ilike', "%{$nome}%");
            }

            if ($email = $request->get('email')) {
                $data[] = ['Filtro de email aplicado: ', $email];
                $email = mb_strtolower($email);
                $q->where(DB::raw('lower(email)'), 'ilike', "%{$email}%");
            }

            if ($telefone = $request->get('telefone')) {
                $data[] = ['Filtro de telefone aplicado: ', $telefone];
                $q->where('telefone', 'ilike', "%{$telefone}%");
            }

            if ($estabelecimento = $request->get('estabelecimento')) {
                $data[] = ['Filtro de estabelecimento aplicado: ', ClienteLojaEstabelecimento::find($estabelecimento)->nome];
                $q->where('estabelecimento_id', $estabelecimento);
            }
        });

        $countRow = count($data);

        $lista = $lista->get();
        $data[] = [];
        $data[] = [
            'Nome', 'Email', 'Telefone', 'Estabelecimento', 'Loja Vinculada',
        ];
        foreach ($lista as $item) {
            $data[] = [
                $item->nome, $item->email, $item->telefone, $item->estabelecimento->nome, $item->loja->nome,
            ];
        }

        \Excel::create('clientes', function ($excel) use ($data, $countRow) {
            $excel->sheet('Clientes', function ($sheet) use ($data, $countRow) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($data, null, 0, null, null);
                $sheet->row($countRow + 2, function ($row) {
                    $row
                        ->setFontWeight(true);
                });
            });
        })->export('xls');
    }
}
