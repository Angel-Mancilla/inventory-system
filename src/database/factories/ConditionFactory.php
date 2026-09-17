<?php

namespace Database\Factories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Condition>
 */
class ConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Nuevo', 'Usado', 'Reacondicionado']),
            'warranty_days' => fake()->randomElement([0, 30, 90, 180, 365]),
            'is_active' => true,
        ];
    }
}
