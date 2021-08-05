<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'creator_id',
        'permission',
        'expires_at',
        'token'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }

    public function campaign()
    {
        return $this->hasOne('App\Models\Campaign', 'id', 'campaign_id');
    }
}
