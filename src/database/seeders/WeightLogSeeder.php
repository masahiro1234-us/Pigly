<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeightLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WeightLogSeeder extends Seeder
{
    public function run(): void
    {
        // UserSeeder で作ったユーザーと同じメールに合わせてください
        $user = User::where('email', 'test@example.com')->firstOrFail();

        // 35日分（今日を含む場合は start の日付を調整）
        $start = Carbon::today()->subDays(34);

        for ($i = 0; $i < 35; $i++) {
            WeightLog::factory()
                ->for($user) // user_id を自動で紐付け
                ->state([
                    'date' => $start->copy()->addDays($i)->toDateString(),
                ])
                ->create();
        }
    }
}