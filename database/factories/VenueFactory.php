<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venue>
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Venue::class;

    public function definition(): array
    {
        return [
            'id' => '1',
            'name' => $this->faker->company . ' Arena',
            'location' => $this->faker->city,
            'description' => 'Opis',
            'is_deleted' => false,
        ];
    }
}
