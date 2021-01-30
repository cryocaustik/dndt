<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CampaignPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CampaignPermissionController extends Controller
{
    public function list()
    {
        $permissions = CampaignPermission::with('user', 'campaign')
            ->where('user_id', Auth::id())
            ->orWhereIn(
                'campaign_id',
                CampaignPermission::where('user_id', Auth::id())
                    ->whereIn('permission', ['owner', 'administrator'])
                    ->pluck('campaign_id')
            )
            ->get();

        return response()->json($permissions);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        unset($data['campaign_id']);
        unset($data['user_id']);

        $rules = [
            'id' => ['required', 'exists:campaign_permissions,id'],
            'permission' => ['required', 'string']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $permission = CampaignPermission::find($data['id']);
        if(!$permission){
            return response()->json(['msg' => 'Permission not found', 'permission' => $data]);
        }

        if(!Gate::forUser(Auth::user())->allows('update', $permission)){
            return response()->json(['msg' => 'unauthorized to update'], 401);
        }

        $permission->fill($data);
        if($permission->isDirty()){
            $permission->save();
            $msg = ['success' => 'Permission has been updated!'];
        } else {
            $msg = ['info' => 'Permission did not have any changes.'];
        }
        return response()->json($msg, 204);
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => ['required', 'exists:campaign_permissions,id']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $permission = CampaignPermission::find($data['id']);
        if(!$permission){
            return response()->json(['msg' => 'Permission not found', 'permission' => $data]);
        }

        if(!Gate::forUser(Auth::user())->allows('delete', $permission)){
            return response()->json(['msg' => 'unauthorized to delete'], 401);
        }

        $permission->delete();
        return response()->json('Permission deleted', 202);
    }
}
