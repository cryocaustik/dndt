<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accounts()
    {
        return $this->hasMany('App\Models\LinkedSocialAccount');
    }

    public function campaignPermissions()
    {
        return $this->hasMany('App\Models\CampaignPermission', 'user_id');
    }

    public function campaigns()
    {
        return $this->hasManyThrough(
            'App\Models\Campaign',
            'App\Models\CampaignPermission',
            'user_id',
            'id',
            'id',
            'campaign_id'
        );
    }
}
