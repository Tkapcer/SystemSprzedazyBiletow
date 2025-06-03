<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
             $model = Event::class;
        return [
            //
            'name' => 'Heathers',
            'description' => 'Opis',
            'event_date' => now()->addDays(7),
            'image_path' => 'zdjPlakat.png',
           'status' => 'approved',
           //'organizer_id' => '1',
          //'venue_id' => '1',
        ];
    }
}
