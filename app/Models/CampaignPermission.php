<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
    ];

    protected $visible = [
        'campaign_id',
        'owner_id',
        'permission',
        'token',
        'expires_at'
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
