<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'campaign_id' => $this->faker->numberBetween(1, 10),
            'item' => $this->faker->randomElement([
                'Armor of Gleaming',
                'Cast-Off Armor',
                'Shield of Expression',
                'Smoldering Armor',
                'Adamantine Armor',
                'Mariners Armor',
                'Mithral Armor',
                'Sentinel Shield',
                'Shield, +1',
                'Armor of Resistance',
                'Armor of Vulnerability',
                'Armor, +1',
                'Arrow-catching Shield',
                'Elven Chain',
                'Glamoured Studded Leather',
                'Shield of Missile Attraction',
                'Shield, +2',
                'Animated Shield',
                'Armor, +2',
                'Demon Armor',
                'Dragon Scale Mail',
                'Dwarven Plate',
                'Shield, +3',
                'Spellguard Shield',
                'Armor of Invulnerability',
                'Armor, +3',
                'Efreeti Chain',
                'Plate Armor of Etherealness',
                'Potion of Climbing',
                'Potion of Healing',
                'Oil of Slipperiness',
                'Philter of Love',
                'Potion of Animal Friendship',
                'Potion of Fire Breath',
                'Potion of Greater Healing',
                'Potion of Growth',
                'Potion of Hill Giant Strength',
                'Potion of Poison',
                'Potion of Resistance',
                'Potion of Water Breathing',
                'Elixir of Health',
                'Oil of Etherealness',
                'Potion of Clairvoyance',
                'Potion of Diminution',
                'Potion of Fire Giant Strength',
                'Potion of Frost Giant Strength',
                'Potion of Gaseous Form',
                'Potion of Heroism',
                'Potion of Invulnerability',
                'Potion of Mind Reading',
                'Potion of Stone Giant Strength',
                'Potion of Superior Healing',
                'Oil of Sharpness',
                'Potion of Cloud Giant Strength',
                'Potion of Flying',
                'Potion of Invisibility',
                'Potion of Longevity',
                'Potion of Speed',
                'Potion of Supreme Healing',
                'Potion of Vitality',
                'Potion of Storm Giant Strength',
                'Ring of Jumping',
                'Ring of Mind Shielding',
                'Ring of Swimming',
                'Ring of Warmth',
                'Ring of Water Walking',
                'Ring of Animal Influence',
                'Ring of Evasion',
                'Ring of Feather Falling',
                'Ring of Free Action',
                'Ring of Protection',
                'Ring of Resistance',
                'Ring of Spell Storing',
                'Ring of X-ray Vision',
                'Ring of the Ram',
                'Ring of Regeneration',
                'Ring of Shooting Stars',
                'Ring of Telekinesis',
                'Ring of Air Elemental Command',
                'Ring of Djinni Summoning',
                'Ring of Earth Elemental Command',
                'Ring of Fire Elemental Command',
                'Ring of Invisibility',
                'Ring of Spell Turning',
                'Ring of Three Wishes',
                'Ring of Water Elemental Command',
                'Immovable Rod',
                'Rod of the Pact Keeper, +1',
                'Rod of Rulership',
                'Rod of the Pact Keeper, +2',
                'Tentacle Rod',
                'Rod of Absorption',
                'Rod of Alertness',
                'Rod of Security',
                'Rod of the Pact Keeper',
                'Rod of Lordly Might',
                'Rod of Resurrection']),
            'source' => $this->faker->userName,
            'notes' => '',
            'quantity' => $this->faker->numberBetween(1, 100),
            'occurred_on' => $this->faker->dateTime,
            'held_by' => $this->faker->name,
            'claimed' => $this->faker->boolean,
            'claimed_by' => $this->faker->name,
            'in_bag_of_holding' => $this->faker->boolean,
            'created_by' => $this->faker->numberBetween(1, 10),
            'modified_by' => $this->faker->numberBetween(1, 10),
        ];
    }
}
