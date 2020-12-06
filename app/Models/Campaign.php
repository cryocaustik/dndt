<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public function owner()
    {
        return $this->hasOne('App\Models\User', 'id');
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\CampaignPermission', 'campaign_id');
    }

}
