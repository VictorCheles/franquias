<?php

namespace App\Http\Controllers\Franquias\Admin;

use DB;
use App\User;
use Validator;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Http\Request;
use App\Jobs\NovoPedidoEmail;
use App\Jobs\EdicaoPedidoEmail;
use App\Jobs\VerificacaoPedido;
use App\Http\Controllers\Controller;

class PedidoController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.pedido.';

    public function __construct()
    {
        $this->middleware('bloqueio_pedido')->only(['create', 'store']);
        $this->middleware('acl:' . implode(',', [Recurso::PUB_PEDIDOS]))->only(['index', 'create', 'turnPedidoRecebido']);

        $this->middleware('acl:' . implode(',', [Recurso::ADM_FORNECIMENTO_PEDIDOS_LISTAR]))->only(['pedidosAbertos']);
        $this->middleware('acl:' . implode(',', [Recurso::ADM_FORNECIMENTO_PEDIDOS_DELETAR]))->only(['destroy']);
        $this->middleware('acl:' . implode(',', [Recurso::ADM_FORNECIMENTO_PEDIDOS_EDITAR]))->only(['edit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = Auth()->user();
        $lista = Pedido::with('loja')->where(function ($q) use ($request, $auth) {
            if ($status = $request->input('filter.status')) {
                $q->where('status', $status);
            }

            if (! $auth->isAdmin()) {
                $q->whereIn('loja_id', $auth->lojas->pluck('id')->toArray());
            } else {
                if ($loja = $request->input('filter.loja')) {
                    $q->where('loja_id', $loja);
                }
            }
        })->orderBy('created_at', 'desc')->paginate(10);

        return view(self::VIEWS_PATH . '.listar', compact('lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $lista = Produto::disponivel()->orderBy('nome')->get();
        $ultimoPedido = false;
        $produtos = [];
        if ($loja_id = $request->get('loja_id')) {
            $pedidos = Pedido::select('id')->whereLojaId($loja_id)->get();
            $ultimoPedido = Pedido::select('id')->whereLojaId($loja_id)->orderBy('created_at', 'desc')->take(1)->get()->first();
            if ($request->get('ultimo') or $request->get('media')) {
                $pedidos = Pedido::with('produtos')->where(function ($q) use ($request, $loja_id) {
                    if ($ultimo = $request->get('ultimo')) {
                        $q->whereId($ultimo);
                    }

                    $q->whereLojaId($loja_id);
                })->get();

                $pedidos->each(function (Pedido $pedido) use (&$produtos) {
                    $pedido->produtos->each(function (Produto $produto) use (&$produtos) {
                        if (isset($produtos[$produto->id])) {
                            $produtos[$produto->id]['quantidade'] += $produto->pivot->quantidade;
                            $produtos[$produto->id]['contador'] += 1;
                        } else {
                            $produtos[$produto->id] = [
                                'disponivel' => $produto->disponivel,
                                'model' => $produto,
                                'quantidade' => $produto->pivot->quantidade,
                                'contador' => 1,
                            ];
                        }
                    });
                });
            }
        }

        if (! empty($produtos)) {
            foreach ($produtos as $k => $v) {
                $produtos[$k]['media_quantidade'] = (int) ceil($v['quantidade'] / $v['contador']);
            }
        }

        return view(self::VIEWS_PATH . '.criar', compact('lista', 'loja', 'produtos', 'ultimoPedido', 'pedidos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, ['loja_id' => 'required']);

        $ids = [];
        $itensQuantidades = $request->get('quantidade');
        foreach ($itensQuantidades as $id => $qtd) {
            if ($qtd > 0) {
                $validator = Validator::make(['id' => $id], [
                    'id' => 'exists:produtos,id',
                ]);
                if ($validator->fails()) {
                    return redirect(route('pedido.create'))->withErrors($validator);
                }
                $ids[] = $id;
            } else {
                unset($itensQuantidades[$id]);
            }
        }

        if (count($itensQuantidades) == 0) {
            return redirect(route('pedido.create'))->withErrors('Nenhum produto foi selecionado');
        }

        $lista = Produto::whereIn('id', $ids)->get();
        $franquia = $request->get('loja_id');
        $loja = Loja::find($franquia);
        $valor_minimo = $loja->valor_minimo_pedido;
        $valor_total = 0;
        $sync_data = [];
        $lista->each(function (Produto $item) use (&$valor_total, $itensQuantidades, &$sync_data) {
            $sync_data[$item->id] = [
                'quantidade' => $itensQuantidades[$item->id],
                'preco' => $item->preco,
            ];
            $valor_total += $item->preco * $itensQuantidades[$item->id];
        });

        if ($valor_total < $valor_minimo) {
            return redirect(route('pedido.create'))->withErrors('Seu pedido não atingiu o valor mínimo');
        }

        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'loja_id' => $loja->id,
                'status' => Pedido::STATUS_SOLICITADO,
                'multa' => $loja->pedidoForaDoPrazo() ? env('PEDIDO_ATRAZADO_MULTA', 300.00) : 0.00,
                'observacoes' => $request->get('observacoes'),
            ]);

            $pedido->produtos()->sync($sync_data);

            $u = new User();
            $u->nome = 'Fornecimento';
            $u->email = env('EMAIL_PEDIDO');

            $this->dispatch(new NovoPedidoEmail(collect([Auth()->user(), $u]), $pedido));

            DB::commit();

            return redirect(route('pedido.index'))->with('success', 'pedido efetuado com sucesso!');
        } catch (\Execption $ex) {
            DB::rollBack();

            return redirect(route('pedido.create'))->withErrors($ex->getMessage());
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
            $item = Pedido::findOrFail($id);

            return view(self::VIEWS_PATH . '.editar', compact('item'));
        } catch (\Execption $ex) {
            return redirect(route('pedido.index'))->withErrors('Pedido não encontrado');
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
            $item = Pedido::findOrFail($id);
        } catch (\Execption $ex) {
            return redirect(route('pedido.index'))->withErrors('Pedido não encontrado');
        }

        $validator = Validator::make($request->all(), [
            'data_entrega' => 'required|date',
            'status' => 'required',
            'produto.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pedido.edit', $item->id)->withErrors($validator)->withInput();
        }

        $produtos = Produto::whereIn('id', array_keys($request->get('produto')))->get();
        $sync_data = [];
        $produtos->each(function (Produto $item) use ($request, &$sync_data) {
            $quantidade = $request->get('produto')[$item->id];
            if ($quantidade > 0) {
                $sync_data[$item->id] = [
                    'quantidade' => $quantidade,
                    'preco' => $item->preco,
                ];
            }
        });

        if ($request->get('novo_produto')) {
            $novos = Produto::whereIn('id', array_keys($request->get('novo_produto')))->get();
            $novos->each(function (Produto $item) use ($request, &$sync_data) {
                $quantidade = $request->get('novo_produto')[$item->id];
                if ($quantidade > 0) {
                    $sync_data[$item->id] = [
                        'quantidade' => $quantidade,
                        'preco' => $item->preco,
                    ];
                }
            });
        }

        DB::beginTransaction();
        try {
            $item->status = $request->get('status');
            $item->data_entrega = $request->get('data_entrega');
            if (Auth()->user()->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR])) {
                $item->multa = unmaskMoney($request->get('multa'));
            }
            $item->save();
            $item->produtos()->sync($sync_data);

            $u = new User();
            $u->nome = 'Fornecimento';
            $u->email = env('EMAIL_PEDIDO');
            $dests = $item->loja->users;
            $dests->push($u);
            $this->dispatch(new EdicaoPedidoEmail($dests, $item));

            DB::commit();

            return redirect()->back()->with('success', 'Pedido autalizado com sucesso');
        } catch (\Execption $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Pedido::findOrFail($id);
            $item->produtos()->detach();
            $item->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Pedido excluído com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    public function pedidosAbertos(Request $request)
    {
        $lista = Pedido::with('loja', 'produtos')->where(function ($q) use ($request) {
            $q->where('status', '!=', Pedido::STATUS_RECEBIDO);
            if ($loja = $request->input('filter.loja')) {
                $q->where('loja_id', '=', $loja);
            }
            if ($praca = $request->input('filter.praca')) {
                $q->whereHas('loja', function ($qq) use ($praca) {
                    $qq->where('praca_id', $praca);
                });
            }
        })->get();

        $query = Pedido::select('produtos.nome', 'produtos.peso', DB::raw('sum(pedido_produtos.quantidade) as quantidade'))
            ->whereNotIn('pedidos.status', [Pedido::STATUS_CONCLUIDO, Pedido::STATUS_RECEBIDO])
            ->join('pedido_produtos', 'pedidos.id', '=', 'pedido_produtos.pedido_id')
            ->join('produtos', 'produtos.id', '=', 'pedido_produtos.produto_id')
            ->groupBy('produtos.id')
            ->orderBy('quantidade', 'desc');

        if ($loja = $request->input('filter.loja')) {
            $query->where('pedidos.loja_id', '=', $loja);
        }
        if ($praca = $request->input('filter.praca')) {
            $query->join('lojas', 'pedidos.loja_id', '=', 'lojas.id');
            $query->join('pracas', 'lojas.praca_id', '=', 'pracas.id');
            $query->where('pracas.id', $praca);
        }

        $produtos = $query->get();

        return view(self::VIEWS_PATH . '.fornecimento.pedidos-abertos', compact('lista', 'produtos'));
    }

    public function imprimirPedido($id)
    {
        $item = Pedido::findOrFail($id);

        return view(self::VIEWS_PATH . '.print', compact('item'));
    }

    public function extratoExcel($id)
    {
        try {
            $pedido = Pedido::findOrFail($id);
            $itens = $pedido->produtos()->orderBy('nome')->get();

            $toFormat = [];
            $i = 4;
            $data[] = ["Pedido #{$pedido->id} | Loja {$pedido->loja->nome}"];
            $data[] = [];
            $itens->groupBy('categoria_id')->each(function ($itens, $categoria_id) use (&$data, &$toFormat, &$i) {
                $toFormat[] = $i;
                $data[] = [\App\Models\CategoriaProduto::find($categoria_id)->nome];
                $data[] = ['Nome', 'Preço unitário', 'Quantidade', 'Subtotal'];
                $itens->each(function (\App\Models\Produto $item) use (&$data, &$toFormat, &$i) {
                    $data[] = [
                        'Nome' => $item->nome,
                        'Preço' => maskMoney($item->pivot->preco),
                        'quantidade' => $item->pivot->quantidade,
                        'Subtotal' => maskMoney($item->pivot->preco * $item->pivot->quantidade),
                    ];
                    $i++;
                });
                $i = $i + 4;
                $data[] = [];
                $data[] = [];
            });

            $data[] = ['Observações: ', $pedido->observacoes];
            $data[] = ['Solicitado em: ', $pedido->created_at->format('d/m/Y \a\s H:i:s')];
            $data[] = ['Data prevista de entrega: ', $pedido->data_entrega ? $pedido->data_entrega->format('d/m/Y') : 'data ainda não definida'];
            $data[] = ['Multa por atraso: ', maskMoney($pedido->multa)];
            $data[] = ['Peso total: ', $pedido->pesoTotal() . 'kg'];
            $data[] = ['Valor total: ', maskMoney($pedido->valorTotal() + $pedido->multa)];
            \Excel::create("extrato-de-pedido-{$pedido->id}-loja-" . str_slug($pedido->loja->nome), function ($excel) use ($pedido, $data, $toFormat, $i) {
                $sheetName = substr("pedido {$pedido->id} loja {$pedido->loja->nome}", 0, 30);
                $excel->sheet($sheetName, function ($sheet) use ($data, $toFormat, $i) {
                    $sheet->setOrientation('landscape');
                    $sheet->fromArray($data);
                    foreach ($toFormat as $numRow) {
                        $mergeCells = "A{$numRow}:D{$numRow}";
                        $sheet->mergeCells($mergeCells);
                        $sheet->getStyle($mergeCells)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $sheet->row($numRow, function ($row) {
                            $row->setBackground('#FFC000')
                                ->setFontWeight(true);
                        });
                    }

                    for ($j = $i; $j <= $i + 4; $j++) {
                        $sheet->getStyle('A' . $j)->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                        ]);
                    }
                });
            })->export('xls');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    public function turnPedidoRecebido($pedidoId)
    {
        Pedido::findOrFail($pedidoId)->update([
            'recebido_em' => Carbon::now(),
            'recebido_por_id' => Auth()->id(),
        ]);

        return redirect()->route('pedido.index');
    }

    public function verificarPedidoGet(Request $request, $id)
    {
        $item = Pedido::findOrFail($id);
        $mensagens = $item->pedido_mensagem;
        $mensagens = $mensagens->groupBy(function ($item) {
            return $item->created_at->format('d/m/Y');
        });

        return view(self::VIEWS_PATH . 'verificar', compact('item', 'mensagens'));
    }

    public function verificarPedidoPost(Request $request, $id)
    {
        $this->validate($request, ['mensagem' => 'required']);

        DB::beginTransaction();
        try {
            $pedido = Pedido::findOrFail($id);
            $pedido->pedido_mensagem()->create([
                'user_id' => $request->user()->id,
                'mensagem' => $request->get('mensagem'),
            ]);

            DB::commit();

            if (! Auth()->user()->isAdmin()) {
                $u = new User();
                $u->nome = 'Fornecimento';
                $u->email = env('EMAIL_PEDIDO');
                $destinatario = collect([$u]);
            } else {
                $destinatario = $pedido->loja->users;
            }

            /**
             * @todo
             *
             * $this->dispatch(new VerificacaoPedido($destinatario, $pedido));
             *
             */

            return redirect()->route('pedido.verificacao', $pedido->id)->with('success', 'Mensagem enviada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('pedido.index')->withErrors($ex->getMessage());
        }
    }
}
