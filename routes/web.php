<?php

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

Route::group(['prefix' => 'auth'], function (){
    Auth::routes();
});

Route::group(['prefix' => 'oauth'], function(){
    Route::get('{provider}', [SocialAccountController::class, 'redirectToProvider'])->name('oauth.redirect');
    Route::get('{provider}/callback', [SocialAccountController::class, 'handleProviderCallback']);
});
