<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function summary($campaign_id)
    {
        if (!Gate::forUser(Auth::user())->allows('view', Campaign::find($campaign_id))) {
            return response()->json(['msg' => 'unauthorized to view inventory'], 401);
        }

        $inventory = Inventory::where([
            ['campaign_id', $campaign_id],
            ['claimed', true],
            ['quantity', '<', 0],
        ])
            ->orWhere([
                ['campaign_id', $campaign_id],
                ['claimed', false],
            ])
            ->select(DB::raw("
                item,
                group_concat(distinct held_by separator ',') as held_by,
                sum(quantity) as quantity
            "))
            ->groupBy('item')
            ->having('quantity', '>', 0)
            ->get();
        return response()->json($inventory);
    }

    public function currency($campaign_id)
    {
        if (!Gate::forUser(Auth::user())->allows('view', Campaign::find($campaign_id))) {
            return response()->json(['msg' => 'unauthorized to view inventory'], 401);
        }

        $inventory = Inventory::where([
            ['campaign_id', $campaign_id],
            ['claimed', true],
            ['quantity', '<', 0]
        ])
            ->orWhere([
                ['campaign_id', $campaign_id],
                ['claimed', false],
            ])
            ->whereRaw("lower(item) in ('platinum', 'gold', 'electrum', 'silver', 'copper')")
            ->select(DB::raw(
                "
                item,
                group_concat(distinct held_by separator ',') as held_by,
                sum(quantity) as quantity"
            ))
            ->groupBy('item')
            ->having('quantity', '>', 0)
            ->get();
        return response()->json($inventory);
    }

    public function log($campaign_id)
    {
        if (!Gate::forUser(Auth::user())->allows('view', Campaign::find($campaign_id))) {
            return response()->json(['msg' => 'unauthorized to view inventory'], 401);
        }

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
        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Failed', 'validation' => $validator->errors()], 422);
        }

        $policy = Gate::getPolicyFor('App\Models\Inventory');
        if (!$policy->create(Auth::user(), $data['campaign_id'])) {
            return response()->json(['msg' => 'unauthorized to create inventory'], 401);
        }

        $item = Inventory::create($data);
        return response()->json($item, 201);
    }

    public function storeMultiple(Request $request)
    {
        $data = $request->all();

        // add auth user as creator/modifier
        $user_id = Auth::id();
        $data = array_map(function ($item) use ($user_id) {
            $user_arr = [
                'created_by' => $user_id,
                'modified_by' => $user_id,
            ];
            return array_merge($user_arr, $item);
        }, $data);

        // validate data
        $rules = [
            '*.campaign_id' => ['required', 'exists:campaigns,id'],
            '*.item' => ['required', 'min:2', 'max:500'],
            '*.notes' => ['string', 'nullable'],
            '*.quantity' => ['required', 'numeric'],
            '*.held_by' => ['string', 'nullable'],
            '*.occurred_on' => ['required'],
            '*.in_bag_of_holding' => ['boolean'],
            '*.claimed' => ['boolean'],
            '*.claimed_by' => ['string', 'nullable']
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Failed', 'validation' => $validator->errors()], 422);
        }

        // verify permissions to create on campaign
        $policy = Gate::getPolicyFor('App\Models\Inventory');
        foreach ($data as $item) {
            if (!$policy->create(Auth::user(), $item['campaign_id'])) {
                return response()->json(['msg' => 'unauthorized to create inventory'], 401);
            }
        }

        // create items
        $items = array();
        foreach ($data as $item) {
            array_push($items, Inventory::create($item));
        }

        return response()->json($items, 201);
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
        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Failed', 'validation' => $validator->errors()], 422);
        }

        $existing_item = Inventory::find($data['id']);
        if (!$existing_item) {
            return response()->json(['msg' => 'item not found', 'item' => $data]);
        }

        if (!Gate::allows('update', $existing_item)) {
            return response()->json(['msg' => 'unauthorized to update inventory'], 401);
        }

        $data['modified_by'] = Auth::id();
        $existing_item->fill($data);
        if ($existing_item->isDirty()) {
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
        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Failed', 'validation' => $validator->errors()], 422);
        }

        $existing_item = Inventory::find($data['id']);
        if (!$existing_item) {
            return response()->json('item not found', 400);
        }

        if (!Gate::allows('delete', $existing_item)) {
            return response()->json(['msg' => 'unauthorized to delete inventory'], 401);
        }

        $existing_item->delete();
        return response()->json('item deleted', 202);
    }

    public function import(Request $request)
    {
        $rules = [
            'campaign_id' => [
                'required',
                'exists:campaigns,id'
            ],
            'file' => 'required|mimetypes:application/csv,text/csv,text/plain',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['msg' => 'Validation Failed', 'validation' => $validator->errors()], 422);
        }

        $campaign_id = $request->get('campaign_id');
        if (!Campaign::find($campaign_id)) {
            return response()->json(['msg' => 'Invalid campaign_id'], 422);
        }

        $path = $request->file('file')->store('debug');

        $file = fopen(storage_path("app/{$path}"), 'r');
        $headers = fgetcsv($file);

        // verify required headers exist
        $required_fields = [
            'item',
            'occurred_on',
            'quantity'
        ];
        if (!empty(array_diff($required_fields, $headers))) {
            return response()->json(['msg' => 'Missing required fields'], 422);
        }

        // define data validation rules
        $data_rules = [
            'item' => ['required', 'min:2', 'max:500'],
            'source' => ['string', 'nullable'],
            'notes' => ['string', 'nullable'],
            'quantity' => ['required', 'numeric'],
            'occurred_on' => ['required', 'date'],
            'held_by' => ['string', 'nullable'],
            'claimed' => ['boolean', 'nullable'],
            'claimed_by' => ['string', 'nullable'],
            'in_bag_of_holding' => ['boolean', 'nullable'],
        ];
        $failed = [];
        $success = 0;

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            $record = array_combine($headers, $data);

            if (isset($record['in_bag_of_holding'])) {
                $record['in_bag_of_holding'] = boolval($record['in_bag_of_holding']);
            }
            if (!isset($record['claimed']) && isset($record['claimed_by'])) {
                $record['claimed'] = boolval($record['claimed_by']);
            }

            $validator = Validator::make($record, $data_rules);
            if ($validator->fails()) {
                array_push($failed, [$data, $validator->errors()]);
                continue;
            }

            $record['occurred_on'] = Carbon::parse($record['occurred_on']);
            $record['campaign_id'] = $campaign_id;
            $record['created_by'] = Auth::id();
            $record['modified_by'] = Auth::id();

            Inventory::create($record);
            $success++;
        }

        // delete file locally
        Storage::delete($path);

        // compile response
        $msg = [
            'msg' => 'Inventory imported',
            'imported' => $success,
            'importErrors' => $failed
        ];
        return response()->json($msg, 201);
    }
}
