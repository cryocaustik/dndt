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
            'campaign_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 10),
            'permission' => $this->faker->randomElement(['administrator', 'contributor', 'reader'])
        ];
    }
}
