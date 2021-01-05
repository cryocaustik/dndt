<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \App\Http\Controllers\Api\v1\CampaignController;
use \App\Http\Controllers\Api\v1\InventoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:sanctum']
], function(){
    Route::group(['prefix' => 'campaign'], function(){
        Route::get('/', [CampaignController::class, 'list'])->name('api.campaign.list');
        Route::post('/store', [CampaignController::class, 'store'])->name('api.campaign.store');
        Route::put('/update', [CampaignController::class, 'update'])->name('api.campaign.update');
    });
    Route::group(['prefix' => 'inventory'], function(){
        Route::get('/summary/{campaign_id}', [InventoryController::class, 'summary'])
            ->name('api.inventory.summary');
        Route::get('/log/{campaign_id}', [InventoryController::class, 'log'])->name('api.inventory.log');
        Route::get('/currency/{campaign_id}', [InventoryController::class, 'currency'])
            ->name('api.inventory.currency');
        Route::post('/store', [InventoryController::class, 'store'])->name('api.inventory.store');
        Route::put('/update', [InventoryController::class, 'update'])->name('api.inventory.update');
        Route::post('/delete', [InventoryController::class, 'delete'])->name('api.inventory.delete');
    });
});
