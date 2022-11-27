<?php

namespace App\Http\Controllers\Franquias;

use DB;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use App\Models\Video;
use App\Models\Arquivo;
use App\Models\Enquete;
use App\Models\Vitrine;
use App\Models\Comunicado;
use App\Models\Metas\Meta;
use VideoEmbed\VideoEmbed;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use App\Models\EventoCalendario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('acl:' . Recurso::PUB_BUSCA)->only(['busca']);
    }

    public static function linkBusca($item)
    {
        switch ($item->tipo) {
            case 'arquivos':
                return route('arquivo.download', $item->id);
                break;
            case 'comunicados':
                return url('comunicados/ler', $item->id);
                break;
            case 'videos':
                return route('video.show', $item->id);
                break;
            case 'solicitacoes':
                return route('solicitacao.show', $item->id);
                break;
            default:
                return '#';
                break;
        }
    }

    public static function tipoFormatado($item)
    {
        switch ($item->tipo) {
            case 'arquivos':
                return 'Arquivo';
                break;
            case 'comunicados':
                return 'Comunicado';
                break;
            case 'videos':
                return 'Vídeo';
                break;
            case 'solicitacoes':
                return 'Solicitação';
                break;
            default:
                return '#';
                break;
        }
    }

    public static function buscaThumb($item)
    {
        switch ($item->tipo) {
            case 'arquivos':
                ob_start();
                ?>
                <div style="width: 116px; height: 87px; text-align: center; line-height: 87px; font-size: 30px; color: #000; text-transform: uppercase; background: #ccc">
                    <?php echo \File::extension('uploads/arquivos/' . $item->arquivo); ?>
                </div>
                <?php
                return ob_get_clean();
                break;
            case 'comunicados':
                ob_start();
                ?>
                <div style="width: 116px; height: 87px;">
                    <img style="width: 116px; height: 87px;" src="<?php echo asset('uploads/comunicados/' . $item->imagem); ?>">
                </div>
                <?php
                return ob_get_clean();
                break;
            case 'videos':
                ob_start();
                ?>
                <div style="width: 116px; height: 87px;">
                    <img style="width: 116px; height: 87px;" src="<?php echo VideoEmbed::render($item->url, ['return_thumbnail' => true]) ?>">
                </div>
                <?php
                return ob_get_clean();
                break;
            case 'solicitacoes':
                ob_start();
                ?>
                <div style="width: 116px; height: 87px; border: 1px solid #ccc;"></div>
                <?php
                return ob_get_clean();
                break;
            default:
                return '#';
                break;
        }
    }

    public function index(Request $request)
    {
        if (Auth()->user()->isAdmin()) {
            $comunicadosQueryBuilder = Comunicado::with('destinatarios');
        } else {
            $comunicadosQueryBuilder = Auth()->user()->comunicados();
        }

        $importante = clone($comunicadosQueryBuilder);
        $importante = $importante->importante()->orderBy('comunicados.created_at', 'desc')->first();
        $take = 6;
        if ($importante) {
            $take = 5;
        }

        $comunicados = $comunicadosQueryBuilder->orderBy('comunicados.created_at', 'desc')->take($take)->get();

        if ($importante) {
            $comunicados->prepend($importante);
        }

        $vitrines = Vitrine::where('status', true)->orderBy('ordem', 'asc')->get();
        $solicitacoes = Auth()->user()->solicitacoes()
            ->whereNotIn('status', [Solicitacao::STATUS_FINALIZADA, Solicitacao::STATUS_NEGADA])
            ->orderBy('created_at', 'desc')->take(5)->get();

        $alertPedido = false;
        if (Auth()->user()->lojas->count() > 0) {
            Auth()->user()->lojas->each(function (Loja $loja) use (&$alertPedido) {
                $dataLimite = Carbon::parse($loja->praca->data_limite_pedido);
                if (Carbon::now()->lt($dataLimite)) {
                    if ($dataLimite->diffInHours(Carbon::now()) <= 24) {
                        $alertPedido = true;

                        return false;
                    }
                }
            });
        }

        $calendario = $this->calendarioPequeno();
        $enquete = Auth()->user()->enquetes()->whereNotIn('enquete_id', Auth()->user()->enquetesRespondidas())->whereDoesntHave('comunicado')->first();
        if ($enquete and !$enquete->aberta) {
            $enquete = null;
        }

        $metas = Meta::dentroDoPeriodo()->with('atividades')->where(function ($q) {
            if (! Auth()->user()->isAdmin()) {
                $q->whereIn('loja_id', Auth()->user()->lojas->pluck('id')->toArray());
            }
        })->get();

        $metas->each(function ($item) {
            $item->titulo_com_loja = '[' . $item->loja->nome . '] ' . $item->titulo;
        });

        $pontos_feitos = $metas->map(function ($meta) {
            return 100 * ($meta->atividades->sum('valor') / $meta->valor);
        });

        $pontos_pendentes = $metas->map(function ($meta) {
            return $meta->valor > $meta->atividades->sum('valor') ?
                100 * (($meta->valor - $meta->atividades->sum('valor')) / $meta->valor)
                : 0;
        });

        $show_modal_metas = false;
        if (session()->get('modal_metas') == false) {
            $show_modal_metas = true;
            session()->put('modal_metas', true);
        }

        return view('portal-franqueado.dashboard.index', compact('comunicados', 'vitrines', 'solicitacoes', 'alertPedido', 'enquete', 'calendario', 'metas', 'pontos_feitos', 'pontos_pendentes', 'show_modal_metas'));
    }

    public function calendarioPequeno(Request $request = null)
    {
        $user = Auth()->user();

        if ($request and $request->get('month') and $request->get('year')) {
            $cur_date = Carbon::createFromDate($request->get('year'), $request->get('month'));
            $preview_date = Carbon::createFromDate($request->get('year'), $request->get('month'))->addMonth(-1);
            $next_date = Carbon::createFromDate($request->get('year'), $request->get('month'))->addMonth();
        } else {
            $cur_date = Carbon::now();
            $preview_date = Carbon::now()->addMonth(-1);
            $next_date = Carbon::now()->addMonth();
        }

        $events = EventoCalendario::where(function ($q) use ($user, $cur_date) {
            $q->where(DB::raw('extract (month from inicio)'), $cur_date->month);
            $q->where(DB::raw('extract (year from inicio)'), $cur_date->year);

            $q->whereHas('comunicado', function ($q) {
                $q->whereHas('destinatarios', function ($q) {
                    if (! Auth()->user()->isAdmin()) {
                        $q->where('user_id', Auth()->user()->id);
                    }
                });
            });
        })->orderBy('inicio')->orderBy('fim')->get();

        $events = $events->merge(
            EventoCalendario::where(function ($q) use ($user, $cur_date) {
                $q->where(DB::raw('extract (month from inicio)'), $cur_date->month);
                $q->where(DB::raw('extract (year from inicio)'), $cur_date->year);

                $q->whereHas('pracas', function ($q) {
                    $q->whereHas('lojas', function ($q) {
                        if (! Auth()->user()->isAdmin()) {
                            $q->where('id', Auth()->user()->loja_id);
                        }
                    });
                });
            })->orderBy('inicio')->orderBy('fim')->get()
        );

        $calendarioChunks = collect();
        if ($events->count() > 0) {
            $parts = (int) ceil($events->count() / 2);
            $calendarioChunks = $events->chunk($parts);
        }

        return view('portal-franqueado.dashboard.index.calendario', compact('cur_date', 'preview_date', 'next_date', 'events', 'calendarioChunks'));
    }

    public function calendario()
    {
        $user = Auth()->user();

        $calendarioJsonData = collect();
        $dataPedidoJsonData = collect();
        $solicitacaoJsonData = collect();
        $comunicadoJsonData = collect();
        if ($user->loja and $user->hasRoles([Recurso::PUB_PEDIDOS])) {
            $dataLimite = Carbon::parse(Auth()->user()->loja->praca->data_limite_pedido);
            $pedido = [
                'title' => 'Prazo limite para pedido',
                'start' => $dataLimite->format('Y-m-d H:i:s'),
                'backgroundColor' => '#f39c12',
                'borderColor' => '#f39c12',
                'url' => route('pedido.create'),
                'type' => 'data_pedido',
            ];
            $calendarioJsonData->push($pedido);
            $dataPedidoJsonData->push($pedido);
        }

        if ($user->hasRoles([Recurso::PUB_SOLICITACOES])) {
            $user->solicitacoes()->orderBy('created_at', 'desc')->get()->each(function (Solicitacao $solicitacao) use ($calendarioJsonData, $solicitacaoJsonData) {
                $item = [
                    'title' => $solicitacao->titulo,
                    'start' => $solicitacao->created_at->format('Y-m-d H:i:s'),
                    'backgroundColor' => '#f56954',
                    'borderColor' => '#f56954',
                    'url' => route('solicitacao.show', $solicitacao->id),
                    'type' => 'solicitacao',
                ];
                if ($solicitacao->prazo) {
                    $item['end'] = $solicitacao->prazo->endOfDay()->format('Y-m-d H:i:s');
                }
                $calendarioJsonData->push($item);
                $solicitacaoJsonData->push($item);
            });
        }

        if ($user->hasRoles([Recurso::PUB_COMUNICADOS])) {
            $user->comunicados()->orderBy('comunicados.created_at', 'desc')->get()->each(function (Comunicado $comunicado) use ($calendarioJsonData, $comunicadoJsonData) {
                $item = [
                    'title' => $comunicado->titulo,
                    'backgroundColor' => '#00a65a',
                    'borderColor' => '#00a65a',
                    'url' => url('/comunicados/ler', $comunicado->id),
                    'type' => 'comunicado',
                ];
                if ($comunicado->inicio and $comunicado->fim) {
                    $item['start'] = $comunicado->inicio;
                    $item['end'] = Carbon::parse($comunicado->fim)->endOfDay()->format('Y-m-d H:i:s');
                } else {
                    $item['start'] = $comunicado->created_at->format('Y-m-d H:i:s');
                }
                $calendarioJsonData->push($item);
                $comunicadoJsonData->push($item);
            });
        }

        return view('portal-franqueado.dashboard.calendario', compact('calendarioJsonData', 'dataPedidoJsonData', 'solicitacaoJsonData', 'comunicadoJsonData'));
    }

    public function busca(Request $request)
    {
        $auth = Auth()->user();
        $filter = $request->get('q');
        if ($filter) {
            $arquivos = Arquivo::selectRaw("'arquivos' as tipo, arquivos.id, arquivos.nome as titulo, descricao, arquivos.created_at, arquivo")->where(function ($q) use ($filter) {
                if ($filter != 'Arquivos') {
                    $q->orWhere('nome', 'ilike', "%{$filter}%");
                    $q->orWhere('descricao', 'ilike', "%{$filter}%");
                }
            })->orderBy('created_at', 'desc')->get();

            $comunicados = Comunicado::join('comunicado_destinatarios', 'comunicados.id', '=', 'comunicado_destinatarios.comunicado_id')
                ->join('users', 'comunicado_destinatarios.user_id', '=', 'users.id')
                ->leftJoin('setores', 'comunicados.setor_id', '=', 'setores.id')
                ->selectRaw("'comunicados' as tipo, comunicados.id, comunicados.titulo, comunicados.descricao, comunicados.created_at, imagem")->where(function ($q) use ($filter, $auth) {
                    $q->where('comunicado_destinatarios.user_id', '=', $auth->id);
                    if ($filter != 'Comunicados') {
                        $q->where(function ($q) use ($filter) {
                            $q->orWhere('comunicados.titulo', 'ilike', "%{$filter}%");
                            $q->orWhere('comunicados.descricao', 'ilike', "%{$filter}%");
                        });
                    }
                })->orderBy('comunicados.created_at', 'desc')->get();

            $videos = Video::selectRaw("'videos' as tipo ,id, titulo, descricao, created_at, url")->where(function ($q) use ($filter) {
                if ($filter != 'Vídeos') {
                    $q->orWhere('titulo', 'ilike', "%{$filter}%");
                    $q->orWhere('descricao', 'ilike', "%{$filter}%");
                }
            })->orderBy('created_at', 'desc')->get();

            $solicitacoes = Solicitacao::selectRaw("'solicitacoes' as tipo, id, titulo, descricao, created_at")->where(function ($q) use ($filter, $auth) {
                if (! $auth->isAdmin()) {
                    $q->where('user_id', $auth->id);
                } else {
                    $q->where(function ($q) use ($auth) {
                        $q->orWhere('user_id', $auth->id);
                        $q->orWhereIn('setor_id', $auth->setores->pluck('id')->toArray());
                    });
                }
                if ($filter != 'Solicitações') {
                    $q->where(function ($q) use ($filter) {
                        $q->orWhere('titulo', 'ilike', "%{$filter}%");
                        $q->orWhere('titulo', 'descricao', "%{$filter}%");
                    });
                }
            })->orderBy('created_at', 'desc')->get();

            switch ($filter) {
                case  'Arquivos':
                    $lista = $arquivos;
                    break;
                case 'Comunicados':
                    $lista = $comunicados;
                    break;
                case 'Vídeos':
                    $lista = $videos;
                    break;
                    case 'Solicitações':
                    $lista = $solicitacoes;
                    break;
                default:
                    $lista = $arquivos->merge($comunicados)->merge($videos)->merge($solicitacoes);
                    break;
            }
        } else {
            $lista = null;
        }

        return view('portal-franqueado.dashboard.busca', compact('lista'));
    }
}
