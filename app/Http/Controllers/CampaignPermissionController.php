<?php

namespace App\Http\Controllers;

use App\Models\CampaignPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignPermissionController extends Controller
{
    public function list(Request $request)
    {
        if($request->ajax())
        {
            return datatables()->of(CampaignPermission::where('user_id', Auth::id()))
                ->addColumn('campaign_name', function ($row){
                    return $row->campaign->name;
                })
                ->addColumn('username', function ($row){
                    return $row->user->username;
                })
                ->addColumn('actions' , function ($row){
                    return view('campaignpermission.includes.buttons', ['permission' => $row]);
                })
                ->make(true);
        }
        return view('campaignpermission.list');
    }

    public function edit($id)
    {
        $permissions = CampaignPermission::find($id);
        if(!$permissions->exists())
        {
            return back()->withErrors('invalid campaign permissions record.');
        }
        return view('campaignpermission.edit', ['permissions', $permissions]);
    }
}
