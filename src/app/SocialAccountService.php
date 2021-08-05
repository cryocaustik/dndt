<?php

namespace App;

use App\Models\LinkedSocialAccount;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SocialAccountService
{
    public function findOrCreate(ProviderUser $providerUser, $provider)
    {
        try {
            // if user exists, return user
            $account = LinkedSocialAccount::where('provider_name', $provider)
                ->where('provider_id', $providerUser->getId())
                ->first();

            if($account){
                if(!$account->user->avatar || $account->user->avatar_url){
                    $account->user->avatar_url = $providerUser->getAvatar();
                }
                return $account->user;
            }

            // check if user has a verified email
            if(!$providerUser->user["verified"]){
                Log::info("user email is not verified");
                throw new \Exception("Email is not verified");
            }

            // check if user exists without social account
            $user = User::where('email', $providerUser->getEmail())->first();

            // if user does not exist at all, create user
            if(!$user){
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'username' => $providerUser->getNickname(),
                    'name' => $providerUser->getName(),
                    'avatar_url' => $providerUser->getAvatar(),
                ]);
            }

            // create and link social account to user
            $user->accounts()->create([
                'provider_name' => $provider,
                'provider_id' => $providerUser->getId()
            ]);

            return $user;
        } catch (\Exception $err) {
            throw new \Exception("Email is not verified");
        }
    }
}
