<?php

namespace App\Http\Controllers\Backend;

use App\Models\Loja;
use App\Models\Cupom;
use App\Custom\CouponCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CupomController extends Controller
{
    public function validarCupom(Request $request, CouponCode $couponCode)
    {
        $code = $couponCode->normalize($request->get('cupom'));
        $loja_id = (int) $request->get('loja');
        $cupom = Cupom::findByCode($code);
        $loja = Loja::find($loja_id);
        if ($cupom and $loja) {
            if ($cupom->status() == Cupom::STATUS_VALIDO) {
                $promocao = $cupom->promocao()->first();
                $data = [
                    'title' => 'Cupom validado com sucesso!!',
                    'text' => 'O cupom para a promoção '. $promocao->nome . ' é válido',
                    'type' => 'success',
                ];

                $cupom->status = false;
                $cupom->code = null;
                $cupom->user_id = Auth()->user()->id;
                $cupom->loja_id = $loja->id;
                $cupom->save();
                $promocao->cupons_usados++;
                $promocao->save();
            } elseif ($cupom->status() == Cupom::STATUS_USADO) {
                $data = [
                    'title' => 'Cupom usado',
                    'text' => 'O cupom fornecido já foi utilizado',
                    'type' => 'warning',
                ];
            } else {
                $data = [
                    'title' => 'Cupom vencido',
                    'text' => 'O cupom não está mais disponível',
                    'type' => 'warning',
                ];
            }
        } elseif (! $loja) {
            $data = [
                'title' => 'Selecione uma loja',
                'text' => 'A seleção de loja é obrigatória',
                'type' => 'warning',
            ];
        } else {
            $data = [
                'title' => 'Cupom vencido',
                'text' => 'O cupom não está mais disponível',
                'type' => 'warning',
            ];
        }

        return response()->json($data);
    }

    public function buscarCupom(Request $request)
    {
        $tituloPagina = 'Cupons';
        $subTituloPagina = 'Buscar';
        $lista = false;
        if ($request->get('filter') and ($request->input('filter.code') or $request->input('filter.promocao') or $request->input('filter.status'))) {
            $lista = Cupom::orWhere(function ($q) use ($request) {
                if ($code = $request->input('filter.code')) {
                    $q->orWhere('code', '=', $code);
                    $q->orWhere('codigo_original', '=', $code);
                }
            })->where(function ($q) use ($request) {
                if ($promocao = $request->input('filter.promocao')) {
                    $q->where('promocao_id', '=', $promocao);
                }
                if ($status = $request->input('filter.status')) {
                    if ($status == Cupom::STATUS_USADO) {
                        $q->where('status', false);
                    }
                    if ($status == Cupom::STATUS_VENCIDO) {
                        $q->where('status', true);
                        $q->whereNull('code');
                    }
                    if ($status == Cupom::STATUS_VALIDO) {
                        $q->where('status', true);
                        $q->whereNotNull('code');
                    }
                }
            })->orderBy('status', 'desc')->orderBy('validade_cupom', 'asc')->paginate(10);
        }

        return view('backend.cupons.buscar', compact('tituloPagina', 'subTituloPagina', 'lista'));
    }
}
