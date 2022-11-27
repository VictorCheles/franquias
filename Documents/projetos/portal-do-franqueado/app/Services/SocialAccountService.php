<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\SocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider = 'facebook')
    {
        if (empty($providerUser->getEmail())) {
            throw new \Exception('Fornecimento do email é obrigatório');
        }

        $account = SocialAccount::whereProvider($provider)
            ->where('provider_user_id', $providerUser->getId())
            ->first();

        if ($account) {
            return $account->cliente;
        }

        $account = new SocialAccount([
            'provider_user_id' => $providerUser->getId(),
            'provider' => $provider,
        ]);

        $user = Cliente::where('email', $providerUser->getEmail())->first();

        if (! $user) {
            $user = Cliente::create([
                'email' => $providerUser->getEmail(),
                'nome' => $providerUser->getName(),
            ]);
        }

        $account->cliente()->associate($user);
        $account->save();

        return $user;
    }
}
