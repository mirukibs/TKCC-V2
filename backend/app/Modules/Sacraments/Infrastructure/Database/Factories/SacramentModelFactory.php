<?php

namespace App\Modules\Sacraments\Infrastructure\Database\Factories;

use App\Modules\Sacraments\Infrastructure\Models\SacramentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SacramentModelFactory extends Factory
{
    protected $model = SacramentModel::class;

    public function definition(): array
    {
        return [
            'baptism_status' => $this->faker->randomElement(['not_baptized', 'baptized']),
            'baptism_date' => $this->faker->optional()->date(),
            'baptism_place' => $this->faker->optional()->city(),
            'confirmation_status' => $this->faker->randomElement(['not_confirmed', 'confirmed']),
            'confirmation_date' => $this->faker->optional()->date(),
            'confirmation_place' => $this->faker->optional()->city(),
            'marriage_status' => $this->faker->randomElement(['single', 'married']),
            'marriage_date' => $this->faker->optional()->date(),
            'marriage_place' => $this->faker->optional()->city(),
        ];
    }
}
