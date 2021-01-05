<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function summary($campaign_id)
    {
        $inventory = Inventory::where([
                ['claimed', true],
                ['quantity', '<', 0]
            ])
            ->orWhere([
                ['claimed', false],
                ['campaign_id', $campaign_id]
            ])
            ->select(DB::raw("item, group_concat(held_by separator ',') as held_by, sum(quantity) as quantity"))
            ->groupBy('item')
            ->get();
        return response()->json($inventory);
    }

    public function currency($campaign_id)
    {
        $inventory = Inventory::where([
                ['claimed', true],
                ['quantity', '<', 0]
            ])
            ->orWhere([
                ['claimed', false],
                ['campaign_id', $campaign_id]
            ])
            ->whereRaw("lower(item) in ('platinum', 'gold', 'electrum', 'silver', 'copper')")
            ->select(DB::raw("item, group_concat(held_by separator ',') as held_by, sum(quantity) as quantity"))
            ->groupBy('item')
            ->get();
        return response()->json($inventory);
    }

    public function log($campaign_id)
    {
        $inventory = Inventory::with('campaign')
            ->where('campaign_id', $campaign_id)
            ->get();
        return response()->json($inventory);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['modified_by'] = Auth::id();
        $rules = [
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'item' => ['required', 'min:2', 'max:500'],
            'notes' => ['string', 'nullable'],
            'quantity' => ['required', 'numeric'],
            'held_by' => ['string', 'nullable'],
            'occurred_on' => ['required'],
            'in_bag_of_holding' => ['boolean'],
            'claimed' => ['boolean'],
            'claimed_by' => ['string', 'nullable']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $item = Inventory::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        unset($data['created_by']);

        $rules = [
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'item' => ['required', 'min:2', 'max:500'],
            'notes' => ['string', 'nullable'],
            'quantity' => ['required', 'numeric'],
            'held_by' => ['string', 'nullable'],
            'occurred_on' => ['required'],
            'in_bag_of_holding' => ['boolean'],
            'claimed' => ['boolean'],
            'claimed_by' => ['string', 'nullable']
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $existing_item = Inventory::find($data['id']);
        if(!$existing_item){
            return response()->json(['msg' => 'item not found', 'item' => $data]);
        }

        $data['modified_by'] = Auth::id();
        $existing_item->fill($data);
        if($existing_item->isDirty()){
            $existing_item->save();
            $msg = ['success' => 'Item has been updated!'];
        } else {
            $msg = ['info' => 'Item did not have any changes.'];
        }
        return response()->json($msg, 204);
    }

    public function delete(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, ['id' => 'required', 'exists:inventories']);
        if($validator->fails()){
            return response()->json(['msg' => 'Validation Failed','validation' => $validator->errors()], 422);
        }

        $existing_item = Inventory::find($data['id']);
        if(!$existing_item){
            return response()->json('item not found', 400);
        }
        $existing_item->delete();
        return response()->json('item deleted', 202);
    }
}
