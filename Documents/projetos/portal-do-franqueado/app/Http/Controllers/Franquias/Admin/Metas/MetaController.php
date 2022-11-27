<?php

namespace App\Http\Controllers\Franquias\Admin\Metas;

use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\Metas\Meta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Metas\Atividade;
use DB;
use App\Http\Controllers\Controller;

class MetaController extends Controller
{
    const VIEWS_PATH = 'portal-franqueado.admin.modulo-metas.metas.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_METAS)->only(['index', 'show']);
        $this->middleware('acl:' . Recurso::ADM_METAS_CRIAR)->only(['create', 'store']);
        $this->middleware('acl:' . Recurso::ADM_METAS_EDITAR)->only(['edit', 'update']);
        $this->middleware('acl:' . Recurso::ADM_METAS_DELETAR)->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quick_actions = $this->quickActionButtons([
            'create' => [
                'title' => 'Criar metas',
                'url' => route('admin.modulo-de-metas.metas.create'),
                'icon' => 'fa fa-plus',
                'resource' => Recurso::ADM_METAS_CRIAR,
            ],
        ]);

        $lista = Meta::with('atividades')->where(function ($q) use ($request) {
            if (Auth()->user()->isAdmin()) {
                if ($lojas = $request->input('filter.loja_id')) {
                    $q->whereIn('loja_id', $lojas);
                }
            } else {
                $q->whereIn('loja_id', Auth()->user()->lojas->pluck('id')->toArray());
            }

            if (!$request->input('filter')) {
                $q->dentroDoPeriodo();
            } else {
                if($periodo = $request->input('filter.periodo')) {
                    list($inicio, $fim) = explode(' - ', $periodo);
                    $q->where(DB::raw('date(inicio)'), '>=', Carbon::createFromFormat('d/m/Y', $inicio));
                    $q->where(DB::raw('date(fim)'), '<=' , Carbon::createFromFormat('d/m/Y', $fim));
                }
            }

        })->orderBy('fim')->get();

        $lista->each(function ($item) {
            $item->titulo_com_loja = '[' . $item->loja->nome . '] ' . $item->titulo;
            $item->progresso = 100 * $item->atividades->sum('valor') / $item->valor;
        });

        $metas = $lista;

        $pontos_feitos = $metas->map(function ($meta) {
            return 100 * ($meta->atividades->sum('valor') / $meta->valor);
        });

        $pontos_pendentes = $metas->map(function ($meta) {
            return $meta->valor > $meta->atividades->sum('valor') ?
                100 * (($meta->valor - $meta->atividades->sum('valor')) / $meta->valor)
                : 0;
        });

        return view(
            self::VIEWS_PATH . 'listar',
            compact('lista', 'quick_actions', 'pontos_feitos', 'pontos_pendentes')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_actions = $this->quickActionButtons([

            'create' => [
                'title' => 'Voltar para a lista',
                'url' => route('admin.modulo-de-metas.metas.index'),
                'icon' => 'fa fa-arrow-left',
                'resource' => Recurso::PUB_METAS,
            ],
        ]);

        $metricas = Meta::$mapMetricas;
        $lojas = Loja::all();

        return view(self::VIEWS_PATH . 'criar', compact('quick_actions', 'metricas', 'lojas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => ['required', 'string', 'max:100'],
            'periodo_meta' => ['required', 'date_range'],
            'valor' => ['required'],
            'metrica' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            $meta = new Meta();
            $meta->fill([
                'titulo' => $request->get('titulo'),
                'valor' => $request->get('valor'),
                'metrica' => $request->get('metrica'),
                'loja_id' => $request->get('loja_id'),
            ]);
            $meta->periodo = $request->get('periodo_meta');
            $meta->save();

            DB::commit();

            return redirect()->route('modulo-de-metas')->with('success', 'Meta cadastrada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.create')->withErrors($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Meta::findOrFail($id);

        $atividades_grafico = $item->atividades()->orderBy('created_at')->get();

        $item->titulo_com_loja = '[' . $item->loja->nome . '] ' . $item->titulo;

        $metas = collect([$item]);

        $pontos_feitos = $metas->map(function ($meta) {
            return 100 * ($meta->atividades->sum('valor') / $meta->valor);
        });

        $pontos_pendentes = $metas->map(function ($meta) {
            return $meta->valor > $meta->atividades->sum('valor') ?
                100 * (($meta->valor - $meta->atividades->sum('valor')) / $meta->valor)
                : 0;
        });

        $views_path = self::VIEWS_PATH;

        return view(self::VIEWS_PATH . 'ver', compact('item', 'views_path', 'atividades_grafico', 'metas', 'pontos_feitos', 'pontos_pendentes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Meta::findOrFail($id);
        $metricas = Meta::$mapMetricas;

        return view(self::VIEWS_PATH . 'editar', compact('item', 'metricas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'titulo' => ['required', 'string', 'max:100'],
            'periodo_meta' => ['required', 'date_range'],
            'valor' => ['required'],
            'metrica' => ['required'],
        ]);

        DB::beginTransaction();
        try {
            $meta = Meta::findOrFail($id);
            $meta->titulo = $request->get('titulo');
            $meta->periodo = $request->get('periodo_meta');
            $meta->valor = $request->get('valor');
            $meta->metrica = $request->get('metrica');
            $meta->save();

            DB::commit();

            return redirect()->route('admin.modulo-de-metas.metas.index')->with('success', 'Meta editada com sucesso');
        } catch (\Exception $ex) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.edit', $id)->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Meta::findOrFail($id);
            $item->atividades->each(function (Atividade $atividade) {
                $atividade->delete();
            });
            $item->delete();

            DB::commit();

            return redirect()->route('admin.modulo-de-metas.metas.index')->with('success', 'Meta deletada com sucesso');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect()->route('admin.modulo-de-metas.metas.index')->withErrors($exception->getMessage());
        }
    }
}
