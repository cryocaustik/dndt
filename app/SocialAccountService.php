<?php

namespace app;

use App\Models\LinkedSocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public function findOrCreate(ProviderUser $providerUser, $provider)
    {
        // if user exists, return user
        $account = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
        if($account){
            return $account->user();
        }

        // check if user exists without social account
        $user = User::where('email', $providerUser->getEmail())->first();

        // if user does not exist at all, create user
        if(!$user){
            $user = User::create([
                'email' => $providerUser->getEmail(),
                'username' => $providerUser->getNickname(),
                'name' => $providerUser->getName(),
            ]);
        }

        // create and link social account to user
        $user->accounts()->create([
            'provider_name' => $provider,
            'provider_id' => $providerUser->getId()
        ]);

        return $user;
    }
}
