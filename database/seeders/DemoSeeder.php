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
            ->times(10)
            ->create();

        // Campaigns
        Campaign::factory()
            ->times(10)
            ->create();

        // CampaignPermissions
        CampaignPermission::factory()
            ->times(20)
            ->create();

        // Inventory
        Inventory::factory()
            ->times(200)
            ->create();
    }
}
