<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeightTarget;
use Illuminate\Database\Seeder;

class WeightTargetSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();

        // そのユーザーに対して目標体重を1件だけ用意
        WeightTarget::updateOrCreate(
            ['user_id' => $user->id],
            ['target_weight' => 60.0]  // 初期値はお好みで
        );
    }
}