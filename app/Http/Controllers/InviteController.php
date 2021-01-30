<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Invite;
use App\Models\CampaignPermission;


class InviteController extends Controller
{
    public function inviteUse($token)
    {
        $invite = Invite::where('token', $token)->first();

        if (!$invite || $invite->expires_at < Carbon::now() || !$invite->active){
            Log::info("Invite token has expired");
            return redirect("/")->withErrors('Invite link has expired!');
        }

        $existingPermissions = CampaignPermission::where([
            ['user_id', Auth::id()],
            ['campaign_id', $invite->campaign_id]
        ])->first();
        if ($existingPermissions){
            Log::info("you already have access to ");
            return redirect("/")
                ->withErrors('you already have access to ' . $existingPermissions->campaign->name);
        }

        $permission = new CampaignPermission;
        $permission->campaign_id = $invite->campaign_id;
        $permission->user_id = Auth::id();
        $permission->permission = $invite->permission;
        $permission->save();

        return redirect("/");
    }
}
