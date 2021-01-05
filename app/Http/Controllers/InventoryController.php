<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    //
    public function summary(Request $request)
    {
        if ($request->ajax())
        {
            return datatables()->of(
                    Inventory::whereIn('campaign_id', CampaignPermission::where('user_id', Auth::id())
                        ->pluck('campaign_id'))
                )
                ->addColumn('campaign', function($row){
                    return $row->campaign->name;
                })
                ->addColumn('actions', function ($row) {
                    return view('inventory.includes.buttons', ['inventory' => $row]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        $campaigns = Campaign::whereIn('id', CampaignPermission::where('user_id', Auth::id())->pluck('campaign_id'))->get();
        return view('inventory.summary', ['campaigns' => $campaigns]);
    }
}
