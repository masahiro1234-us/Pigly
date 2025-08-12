<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WeightLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightLogFactory extends Factory
{
    protected $model = WeightLog::class;

    public function definition(): array
    {
        // 0〜2時間の間で擬似的な運動時間（HH:MM:SS）
        $h = $this->faker->numberBetween(0, 2);
        $m = $this->faker->numberBetween(0, 59);
        $exerciseTime = sprintf('%02d:%02d:00', $h, $m);

        return [
            'user_id'          => User::factory(),
            'date'             => $this->faker->dateTimeBetween('-60 days', 'now')->format('Y-m-d'),
            'weight'           => $this->faker->randomFloat(1, 45, 85),
            'calories'         => $this->faker->numberBetween(1000, 2600),
            'exercise_time'    => $exerciseTime,
            'exercise_content' => $this->faker->randomElement([
                'ランニング', 'ウォーキング', '筋トレ(上半身)', '筋トレ(下半身)', 'ヨガ', 'ストレッチ'
            ]),
        ];
    }
}