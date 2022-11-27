<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\Cupom;
use App\Models\Promocao;
use App\Custom\CouponCode;
use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
use GuzzleHttp\Exception\ClientException;

class SocialController extends Controller
{
    public function redirect($provider = 'facebook', $promocao_id)
    {
        \Session::put('promocao_id', $promocao_id);

        return Socialite::driver($provider)->redirect();
    }

    public function callbackFacebook(CouponCode $couponCode)
    {
        return $this->callback(new SocialAccountService(), $couponCode, 'facebook');
    }

    public function callback(SocialAccountService $accountService, CouponCode $couponCode, $provider)
    {
        try {
            $user = $accountService->createOrGetUser(Socialite::driver($provider)->user());
            $promocao = Promocao::findOrFail(\Session::get('promocao_id'));

            if (! $promocao->status) {
                return redirect('/')->with('error', 'Promoção ' . $promocao->nome . ' finalizada');
            }
        } catch (ClientException $ex) {
            return redirect('/')->withErrors('Autorização com o Facebook falhou');
        } catch (\Exception $ex) {
            return redirect('/')->with('error', 'Promoção finalizada ou inexistente ' . $ex->getMessage());
        }

        $dadosCupom = [
            'cliente_email' => $user->email,
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
}
