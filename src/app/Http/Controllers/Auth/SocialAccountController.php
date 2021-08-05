<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialAccountService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            Log::error($provider . " error on callback", [$err->getMessage()]);
            return redirect()->route('home')->withErrors(["error" => "Invalid Provider"]);
        }

        try {
            $authUser = $accountService->findOrCreate(
                $user,
                $provider
            );
        } catch (\Exception $err) {
            Log::error("Socialite login error", [$err]);
            return redirect()->route("home")->withErrors(["Login error" => $err->getMessage()]);
        }

        Auth::login($authUser, true);
        return redirect()->route("home");
    }
}
