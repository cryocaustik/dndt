<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class CampaignPermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function view(User $user, CampaignPermission $campaignPermission)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function update(User $user, CampaignPermission $campaignPermission)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function delete(User $user, CampaignPermission $campaignPermission)
    {
        $permission = CampaignPermission::where([
            ['user_id', $user->id],
            ['campaign_id', $campaignPermission->campaign_id],
        ])
            ->whereIn('permission', ['owner', 'administrator'])
            ->exists();

        $self = $campaignPermission->user_id === Auth::id();
        return $permission || $self;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function restore(User $user, CampaignPermission $campaignPermission)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function forceDelete(User $user, CampaignPermission $campaignPermission)
    {
        //
    }

    /**
     * Determine whether the user can invite others for the campaign.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CampaignPermission  $campaignPermission
     * @return mixed
     */
    public function invite(User $user, CampaignPermission $campaignPermission)
    {
        return CampaignPermission::where([
                ['id', $campaignPermission->id],
                ['user_id', $user->id],
            ])
            ->whereIn('permission', ['owner', 'administrator'])
            ->exists();
    }
}
