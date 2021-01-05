<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'item',
        'source',
        'notes',
        'quantity',
        'occurred_on',
        'held_by',
        'claimed',
        'claimed_by',
        'in_bag_of_holding',
        'created_by',
        'modified_by',
    ];

    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id');
    }
}
