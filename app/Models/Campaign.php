<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean'
    ];

    protected $fillable = [
        'name',
        'dm',
        'active'
    ];

//    public function owner()
//    {
////        \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
////            dump($query->sql, $query->bindings, $query->time);
////        });
//        return $this->hasOne('App\Models\CampaignPermission')->where('permission', 'owner');
//    }

    public function owner()
    {
        return $this->hasOneThrough(
            User::class,
            CampaignPermission::class,
            'campaign_id',
            'id',
            'id',
            'user_id'
        )->where('campaign_permissions.permission', 'owner');
    }

    public function getOwner()
    {
        return $this->owner->user;
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\CampaignPermission');
    }

}
