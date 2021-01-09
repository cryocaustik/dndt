<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CampaignPermission;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InviteController extends Controller
{
    public function list()
    {
        $invites = Invite::with('user', 'campaign')
            ->whereIn(
                'campaign_id',
                CampaignPermission::where('user_id', Auth::id())
                    ->whereIn('permission', ['owner', 'administrator'])
                    ->pluck('campaign_id')
            )
            ->orWhere('creator_id', Auth::id())
            ->get();

        return response()->json($invites);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'permission' => ['string', Rule::in(['owner', 'administrator', 'contributor', 'reader'])],
            'expires_at' => ['required', Rule::in(['5m', '30m', '1h', '1d', '7d', '30d'])]
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $invite = Invite::create([
            'campaign_id' => $data['campaign_id'],
            'creator_id' => Auth::id(),
            'permission' => $data['permission'],
            'expires_at' => Carbon::now()->add($data['expires_at']),
            'token' => Str::uuid(),
        ]);

        return response()->json($invite, 201);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => ['required', 'exists:invites,id']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $invite = Invite::find($data['id']);
        if(!$invite){
            return response()->json(['msg' => 'Invite not found', 'permission' => $data]);
        }
        $invite->delete();
        return response()->json('Invite deleted', 202);
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

        $permission = new CampaignPermission;
        $permission->campaign_id = $invite->campaign_id;
        $permission->user_id = Auth::id();
        $permission->permission = $invite->permission;
        $permission->save();

        return redirect()->route('home');
    }

}
