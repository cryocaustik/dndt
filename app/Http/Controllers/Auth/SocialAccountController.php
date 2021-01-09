<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use app\SocialAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(SocialAccountService $accountService, $provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $err) {
            return redirect()->route('login')->withErrors('Invalid Provider');
        }

        $authUser = $accountService->findOrCreate(
            $user,
            $provider
        );

        Auth::login($authUser, true);
        return redirect()->route('home');
    }
}
