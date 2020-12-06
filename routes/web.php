<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->name('home');

Route::group(['prefix' => 'auth'], function (){
    Route::get('login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    Route::post('login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login');
    Route::get('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
    Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
});

Route::group([
    'middleware' => ['auth']
], function (){
    Route::get('/campaign', [\App\Http\Controllers\CampaignController::class, 'list'])->name('campaign.list');
});
