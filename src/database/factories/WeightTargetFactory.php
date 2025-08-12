<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeightTarget;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightTargetFactory extends Factory
{
    protected $model = WeightTarget::class;

    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'target_weight' => $this->faker->randomFloat(1, 48, 65),
        ];
    }
}