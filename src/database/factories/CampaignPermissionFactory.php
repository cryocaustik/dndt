<?php

namespace Database\Factories;

use App\Models\CampaignPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignPermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CampaignPermission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'campaign_id' => $this->faker->unique()->numberBetween(1, 100),
            'user_id' => $this->faker->unique()->numberBetween(1, 100),
            'permission' => $this->faker->randomElement(['owner', 'administrator', 'contributor', 'reader'])
        ];
    }
}
