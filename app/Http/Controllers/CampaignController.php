<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of(
                    Campaign::with('owner')
                        ->whereHas('permissions', function ($query) { $query->where('user_id', Auth::id()); })
                )
                ->editColumn('owner', function($row){
                    return $row->owner->username;
                })
                ->addColumn('actions', function ($row) {
                    return view('campaign.includes.buttons', ['campaign' => $row]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('campaign.list');
    }

    public function create()
    {
        return view('campaign.create');
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'dm');

        $campaign = new Campaign;
        $campaign->name = $data['name'];
        $campaign->dm = $data['dm'];
        $campaign->active = (boolean)$request->input('active');
        $campaign->save();

        $permissions = new CampaignPermission;
        $permissions->campaign_id = $campaign->id;
        $permissions->user_id = Auth::user()->id;
        $permissions->permission = 'owner';
        $permissions->save();

        return redirect()->route('campaign.list')->with(['success' => 'campaign was created successfully!']);
    }

    public function edit($id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return back()->withErrors('invalid campaign id');
        }

        if (!Gate::forUser(Auth::user())->allows('update', $campaign)) {
            return back()->withErrors('unauthorized to edit campaign ' . $campaign->name);
        }

        return view('campaign.edit', ['campaign' => $campaign]);
    }

    public function update(Request $request)
    {
        $id = (int)$request->input('id');
        $campaign = Campaign::find($id);

        if(!$campaign->exists()){
            return back()->withErrors('campaign not found.');
        }

        if(!Gate::forUser(Auth::user())->allows('update', $campaign)){
            return back()->withErrors('unauthorized to edit campaign.');
        }

        $data = $request->only('name', 'dm');
        $data['active'] = (boolean)$request->input('active');

//        TODO: Add ability to change the 'owner' permission in CampaignPermission
//        $newOwnerId = User::where('username', $request->input('owner'))->value('id');
//        if(!$newOwnerId)
//            return back()->withErrors('specified owner not found');
//        $data['owner_id'] = $newOwnerId;

        $campaign->fill($data);
        if($campaign->isDirty()){
            $campaign->save();
            $msg = ['success' => 'Campaign has been updated!'];
        } else {
            $msg = ['info' => 'Campaign did not have any changes.'];
        }
        return redirect()->route('campaign.list')->with($msg);
    }

    public function delete($id)
    {
        $campaign = Campaign::find($id);
        if(!$campaign->exists()){
            return back()->withErrors('campaign not found.');
        }

        if(!Gate::forUser(Auth::user())->allows('delete', $campaign)){
            return back()->withErrors('unauthorized to delete campaign.');
        }
        $campaign->delete();
        return redirect()->route('campaign.list')->with(['success' => 'campaign has been deleted!']);
    }

    public function invite(Request $request)
    {
        switch ($request->method())
        {
            case 'POST':
                $data = $request->validate([
                    'campaign_id' => ['bail', 'required', 'exists:campaigns,id'],
                    'expires' => ['required', 'in:5m,30m,1h,1d,7d'],
                    'permission' => ['required', 'in:administrator,contributor,reader'],
                ]);

                if(!Gate::forUser(Auth::user())->allows('invite', Campaign::find($data['campaign_id'])))
                    return response()->json('you are not authorized to invite for this campaign', 401);

                $invite = new Invite;
                $invite->campaign_id = $data['campaign_id'];
                $invite->expires_at = Carbon::now()->add($data['expires']);
                $invite->creator_id = Auth::id();
                $invite->permission = $data['permission'];
                $invite->token = Str::uuid();
                $invite->save();

                return response()->json(['invite' => route('campaign.invite.use', $invite->token)]);
            default:
                return view('campaignpermission.invite', ['campaigns' => Auth::user()->campaigns]);
        }
    }

    public function inviteUse(Request $request, $token)
    {
        $invite = Invite::where('token', $token)->first();
        if(!$invite || $invite->expires_at < Carbon::now() || !$invite->active)
            return redirect()->route('home')->withErrors('Invite link has expired!');

        $existingPermissions = CampaignPermission::where([
                ['user_id', Auth::id()],
                ['campaign_id', $invite->campaign_id]
            ])->first();
        if($existingPermissions)
            return redirect()->route('campaign.list')
                ->withErrors('you already have access to '. $existingPermissions->campaign->name);

        Log::debug(['inviteUse' => 'create']);
        $permission = new CampaignPermission;
        $permission->campaign_id = $invite->campaign_id;
        $permission->user_id = Auth::id();
        $permission->permission = $invite->permission;
        $permission->save();

        Log::debug(['inviteUse' => 'return']);
        return redirect()
            ->route('campaign.list')
            ->with(['success' => 'You have been granted access to '. $permission->campaign->name]);
    }
}

