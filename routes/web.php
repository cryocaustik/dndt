<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use \App\Http\Controllers\CampaignPermissionController;

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
    Route::group(['prefix' => 'campaign'], function (){
        Route::get('/', [CampaignController::class, 'list'])->name('campaign.list');
        Route::get('/edit/{id}', [CampaignController::class, 'edit'])->name('campaign.edit');
        Route::post('/update', [CampaignController::class, 'update'])->name('campaign.update');
        Route::post('/delete/{id}', [CampaignController::class, 'delete'])->name('campaign.delete');
        Route::get('/create', [CampaignController::class, 'create'])->name('campaign.create');
        Route::post('/store', [CampaignController::class, 'store'])->name('campaign.store');
        Route::get('/invite', [CampaignController::class, 'invite'])->name('campaign.invite');
        Route::post('/invite', [CampaignController::class, 'invite'])->name('campaign.invite');
        Route::get('/invite/{token}', [CampaignController::class, 'inviteUse'])->name('campaign.invite.use');
    });

    Route::group(['prefix' => 'campaignpermission'], function(){
        Route::get('/', [CampaignPermissionController::class, 'list'])->name('campaign.permission.list');
        Route::get('/edit/{id}', [CampaignPermissionController::class, 'edit'])->name('campaign.permission.edit');
        Route::get('/invite', [CampaignPermissionController::class, 'invite'])->name('campaign.permission.invite');
        Route::post('/invite', [CampaignPermissionController::class, 'invite'])->name('campaign.permission.invite');
    });
});
