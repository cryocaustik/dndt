<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    public function list()
    {
        $campaigns = Campaign::with('owner')
            ->whereIn('id', CampaignPermission::where('user_id', Auth::id())->pluck('campaign_id'))
            ->get();
        return response()->json($campaigns);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => ['required', 'min:2', 'max:500'],
            'dm' => ['string', 'nullable'],
            'in_bag_of_holding' => ['boolean']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $campaign = Campaign::create($data);
        $ownerPermission = CampaignPermission::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'permission' => 'owner'
        ]);
        return response()->json($campaign, 201);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => ['required', 'exists:campaigns,id'],
            'name' => ['required', 'string'],
            'dm' => ['string', 'nullable'],
            'active' => ['boolean'],
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $campaign = Campaign::find($data['id']);
        if(!$campaign){
            return response()->json(['msg' => 'item not found', 'item' => $data]);
        }

        if(!Gate::forUser(Auth::user())->allows('update', $campaign)){
            return response()->json(['msg' => 'unauthorized to update'], 401);
        }

        $data['modified_by'] = Auth::id();
        $campaign->fill($data);
        if($campaign->isDirty()){
            $campaign->save();
            $msg = ['success' => 'Campaign has been updated!'];
        } else {
            $msg = ['info' => 'Campaign did not have any changes.'];
        }
        return response()->json($msg, 204);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => ['required', 'exists:campaigns,id']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $campaign = Campaign::find($data['id']);
        if(!$campaign){
            return response()->json(['msg' => 'Campaign not found not found', 'campaign' => $data]);
        }

        if(!Gate::forUser(Auth::user())->allows('delete', $campaign)){
            return response()->json(['msg' => 'unauthorized to delete'], 401);
        }

        $campaign->delete();
        return response()->json('Campaign deleted', 202);
    }
}
