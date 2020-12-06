<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function list(Request $request)
    {
        $campaigns = Campaign::whereHas('permissions', function($q){
            $q->where('user_id', Auth::user()->id);
        })->get();
        return view('campaign.list', ['campaigns' => $campaigns]);
    }
}
