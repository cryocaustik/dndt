<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\SocialAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
})->name('home');

//Route::group(['prefix' => 'auth'], function (){
////    Auth::routes();
//    Route::get('login', function(){
//        return redirect('/login');
//    })->name('login');
//    Route::post('login', [LoginController::class, 'login'])->name('login');
//    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
//});

Route::group(['prefix' => 'oauth'], function(){
    Route::get('{provider}', [SocialAccountController::class, 'redirectToProvider'])->name('oauth.redirect');
    Route::get('{provider}/callback', [SocialAccountController::class, 'handleProviderCallback']);
});

Route::group([
//    'prefix' => 'w',
    'middleware' => 'auth'
], function() {
    Route::get('/invite/{token}', [\App\Http\Controllers\InviteController::class, 'inviteUse'])->name('invite.use');
});

