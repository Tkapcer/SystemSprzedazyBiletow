<?php

namespace Database\Factories;

use App\Models\LoginQueue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoginQueue>
 */
class LoginQueueFactory extends Factory
{
    protected $mdoel = LoginQueue::Class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'position' => '1',
        ];
    }
}
