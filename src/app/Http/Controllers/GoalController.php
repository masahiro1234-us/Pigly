<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightTarget;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $target = WeightTarget::firstOrCreate(
            ['user_id' => $user->id],
            ['target_weight' => 0]
        );

        return view('weight_logs.goal_setting', [
            'targetWeight' => $target->target_weight,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'target_weight' => ['required','numeric','min:0','max:300'],
        ]);

        // 小数1桁に丸めたい場合（不要なら削除）
        $validated['target_weight'] = round($validated['target_weight'], 1);

        $user = Auth::user();

        WeightTarget::updateOrCreate(
            ['user_id' => $user->id],
            ['target_weight' => $validated['target_weight']]
        );

        // ← ここはレイアウトに合わせて success / status のどちらかで統一
        return redirect()->route('weight_logs.index')
            ->with('status', '目標体重を更新しました。');
    }
}