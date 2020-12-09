<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignPermission;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users
        User::create([
            'username' => 'dev',
            'email' => 'dev@dev.com',
            'password' => bcrypt('dev')
        ]);
        User::factory()
            ->times(100)
            ->create()
            ->each(function($u){

            });

        // Campaigns
        Campaign::factory()
            ->times(100)
            ->create();

        // CampaignPermissions
        Campaign::all()->each(function($row) {
            CampaignPermission::create([
                'campaign_id' => $row->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'permission' => 'owner'
            ]);
        });
        CampaignPermission::factory()
            ->times(20)
            ->create();

        // Inventory
        Inventory::factory()
            ->times(200)
            ->create();
    }
}
