<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition()
    {
        return [
            'title' => $this->faker->unique()->jobTitle,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
