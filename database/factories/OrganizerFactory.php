<?php

namespace Database\Factories;

use App\Models\Organizer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizerFactory extends Factory
{
    protected $model = Organizer::class;
        protected static ?string $password;
    public function definition(): array
    {
        return [
            'companyName' => 'OpenerFestiwal',
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => 'approved',
        ];
    }
}
