<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\Cupom;
use App\Models\Cliente;
use App\Models\Promocao;
use App\Custom\CouponCode;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Localidade\Municipio;

class HomeController extends Controller
{
    public function index()
    {
        $tituloAba = 'Cupons ' . env('APP_NAME');
        $tituloPagina = '';
        $subTituloPagina = '';
        $agent = new Agent();

        $linkApp = '#';
        if ($agent->isAndroidOS()) {
            $linkApp = 'https://play.google.com/store/apps/details?id=com.neemo.waynes';
        } else if ($agent->is('iPhone')) {
            $linkApp = 'https://itunes.apple.com/br/app/waynes/id1411424514';
        }

        $lista = Promocao::ativasPrimeiro();

        return view('welcome', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'agent', 'lista', 'linkApp'));
    }

    public function promocao(Request $request, $id)
    {
        $tituloPagina = '';
        $subTituloPagina = '';

        try {
            $item = Promocao::findOrFail($id);

            if (!is_null($item->url_externa) or !empty($item->url_externa)) {
                header('Location: ' . $item->url_externa);
                die;
            }
            if (!$item->status) {
                return redirect('/')->with('error', 'Promoção ' . $item->nome . ' finalizada');
            }
        } catch (\Exception $ex) {
            return redirect('/');
        }

        $tituloAba = $item->nome . ' | Promoção';

        return view('detalhes', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'item'));
    }

    public function formCliente(Request $request, $id)
    {
        $tituloPagina = '';
        $subTituloPagina = '';

        try {
            $item = Promocao::findOrFail($id);

            if (!$item->status) {
                return redirect('/')->with('error', 'Promoção ' . $item->nome . ' finalizada');
            }
        } catch (\Exception $ex) {
            return redirect('/');
        }

        $tituloAba = $item->nome . ' | Promoção';

        $municipios = Municipio::all()->each(function (Municipio $municipio) {
            $municipio->nome = $municipio->nome . ' - ' . $municipio->estado->sigla;
        })->pluck('nome', 'id')->toArray();

        return view('form-cliente', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'item', 'municipios'));
    }

    public function cupom(Request $request, CouponCode $couponCode, $id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|min:3',
            'email' => 'required|email',
            'municipio_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect(url()->current())->withErrors($validator);
        }

        try {
            $promocao = Promocao::findOrFail($id);

            if (!$promocao->status) {
                return redirect('/')->with('error', 'Promoção ' . $promocao->nome . ' finalizada');
            }
        } catch (\Exception $ex) {
            return redirect('/');
        }

        try {
            $cliente = Cliente::findOrFail($request->get('email'));
            $cliente->nome = $request->get('nome');
            $cliente->municipio_id = $request->get('municipio_id');
            $cliente->setUpdatedAt(Carbon::now());
            $cliente->save();
        } catch (\Exception $ex) {
            $cliente = Cliente::create([
                'nome' => $request->get('nome'),
                'email' => $request->get('email'),
                'municipio_id' => $request->get('municipio_id'),
            ]);
        }

        $dadosCupom = [
            'cliente_email' => $cliente->email,
            'promocao_id' => $promocao->id,
            'validade_cupom' => $promocao->validade_do_cupom,
        ];

        $code = $couponCode->generate();

        while (Cupom::findByCode($code)) {
            $code = $couponCode->generate();
        }

        $dadosCupom['code'] = $code;
        $dadosCupom['codigo_original'] = $code;

        $promocao->cupons_criados++;
        $promocao->save();

        Cupom::create($dadosCupom);

        return redirect('/promocao/' . $promocao->id . '/cupom/' . $code);
    }

    public function mostrarCupom(Request $request, $id, $cupom)
    {
        try {
            $promocao = Promocao::findOrFail($id);
            $cupom = Cupom::findOrFailByCode($cupom);

            $tituloPagina = '';
            $subTituloPagina = '';
            $tituloAba = 'Cupom | ' . $promocao->nome . ' | Promoção';

            return view('detalhes-cupom', compact('tituloAba', 'tituloPagina', 'subTituloPagina', 'promocao', 'cupom'));
        } catch (\Exception $ex) {
            return redirect('/');
        }
    }

    public function termosCondicoes()
    {
        $tituloPagina = '';
        $subTituloPagina = '';
        $tituloAba = 'Termos e condições | Cupons ' . env('APP_NAME');

        return view('termos-e-condicoes', compact('tituloPagina', 'subTituloPagina', 'tituloAba'));
    }

    public function lojasParticipantes()
    {
        $tituloPagina = '';
        $subTituloPagina = '';
        $tituloAba = 'Lojas Participantes | Cupons ' . env('APP_NAME');

        return view('lojas-participantes', compact('tituloPagina', 'subTituloPagina', 'tituloAba'));
    }
}
