<?php

namespace App\Http\Controllers\AvaliadorOculto;

use DB;
use Validator;
use Carbon\Carbon;
use App\ACL\Recurso;
use App\Models\Loja;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AvaliadorOculto\Formulario;

class DashboardController extends Controller
{
    const VIEWS_PATH = 'avaliador-oculto.';

    public function __construct()
    {
        $this->middleware('acl:' . Recurso::ADM_AVALIADOR_OCULTO_DASHBOARD)->only(['adminDashboard']);
    }

    public function adminDashboard(Request $request)
    {
        $forms = Formulario::with(['users' => function ($q) use ($request) {
            if ($loja_id = $request->input('filter.loja_id')) {
                $q->where('loja_id', $loja_id);
            }
            if ($formulario_id = $request->input('filter.formulario_id')) {
                $q->where('formulario_id', $formulario_id);
            }
        }])->get();

        $avaliacoes_feitas = collect();
        $avaliacoes_feitas_sem_comprovante = collect();
        $avaliacoes_pendentes = collect();
        $lojas_avaliacoes_feitas = 0;
        $lojas_avaliacoes_pendentes = 0;

        $lojas_ids = collect();
        $lojas_array = [];
        $lojas_objs = collect();

        $forms->each(function (Formulario $item) use ($avaliacoes_feitas, $avaliacoes_pendentes, $avaliacoes_feitas_sem_comprovante, $lojas_ids, &$forms_com_pendencias, &$lojas_array) {
            $item->users->each(function ($item) use ($avaliacoes_feitas,$avaliacoes_pendentes, $avaliacoes_feitas_sem_comprovante, $lojas_ids, &$lojas_array) {
                if ($item->pivot->respondido_em and $item->pivot->finalizou) {
                    $avaliacoes_feitas->push($item);
                    $lojas_array[$item->pivot->loja_id][] = $item;
                    $lojas_ids->push($item->pivot->loja_id);
                } elseif ($item->pivot->respondido_em and ! $item->pivot->finalizou) {
                    $avaliacoes_feitas_sem_comprovante->push($item);
                    $lojas_array[$item->pivot->loja_id][] = $item;
                    $lojas_ids->push($item->pivot->loja_id);
                } else {
                    $avaliacoes_pendentes->push($item);
                    $lojas_array[$item->pivot->loja_id][] = $item;
                    $lojas_ids->push($item->pivot->loja_id);
                }
            });
            if ($avaliacoes_pendentes->count() > 0) {
                $forms_com_pendencias += 1;
            }
        });

        foreach ($lojas_array as $loja_id => $avaliacoes) {
            $obj = new \stdClass();
            $obj->loja = Loja::find($loja_id);
            $obj->avaliacoes = $avaliacoes;
            $obj->concluidas = 0;
            $obj->pendentes = 0;
            foreach ($avaliacoes as $av) {
                if ($av->pivot->finalizou) {
                    $obj->concluidas++;
                } else {
                    $obj->pendentes++;
                }
            }
            $lojas_objs->push($obj);
        }

        $lojas_objs->each(function ($item) use (&$lojas_avaliacoes_pendentes, &$lojas_avaliacoes_feitas) {
            if ($item->pendentes != 0) {
                $lojas_avaliacoes_pendentes++;
            } else {
                $lojas_avaliacoes_feitas++;
            }
        });

        return view(self::VIEWS_PATH . 'admin.dashboard', compact('forms', 'avaliacoes_feitas', 'avaliacoes_pendentes', 'lojas_avaliacoes_feitas', 'avaliacoes_feitas_sem_comprovante', 'lojas_avaliacoes_pendentes', 'lojas_objs'));
    }

    public function index(Request $request, Agent $agent)
    {
        if (Auth()->guard('avaliador_oculto')->user()->aceite == 1) {
            return redirect()->route('dashboard');
        }

        return view(self::VIEWS_PATH . 'termos');
    }

    public function postIndex(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aceite' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Auth()->guard('avaliador_oculto')->user()->update(['aceite' => 1, 'data_aceite' => Carbon::now()]);
            DB::commit();

            return redirect()->route('dashboard');
        } catch (\Exception $exception) {
            DB::rollBack();

            return redirect('/')->withErrors($exception->getMessage());
        }
    }

    public function dashboard(Request $request)
    {
        $user = Auth()->guard('avaliador_oculto')->user();

        $formulario = $user->formularios()->where('finalizou', false)->get();

        if ($formulario->count() == 0 and $ultimoRespondido = $user->formularios()->wherePivot('data_termino', '!=', null)->orderBy('pivot_data_termino', 'desc')->first()) {
            $loja = Loja::find($ultimoRespondido->pivot->loja_id);

            return view(self::VIEWS_PATH . 'admin.formularios.programa-concluido', compact('ultimoRespondido', 'loja'));
        }

        $current = false;
        $loja = false;
        foreach ($formulario as $f) {
            if ($f->pivot->observacoes && ! $f->pivot->foto_comprovante) {
                return redirect()->route('enviar.comprovante', [$f->id, $f->pivot->loja_id]);
            }
            $current = $f;
            continue;
        }

        if ($current) {
            $loja = Loja::find($current->pivot->loja_id);
        }

        return view(self::VIEWS_PATH . 'dashboard', compact('current', 'loja'));
    }
}
