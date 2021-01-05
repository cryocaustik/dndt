<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->input('remember_me', false);

        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed...
            return redirect()->intended();
        }
        else {
            return back()->withErrors('login failed');
        }
    }
}
