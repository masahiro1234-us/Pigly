<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WeightTarget;
use App\Models\WeightLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Step1表示
    public function showStep1()
    {
        return view('register.step1');
    }

    // Step1送信 → ユーザー登録してStep2へ
    public function postStep1(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // ユーザー作成
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // セッションにuser_idを保存してstep2へ
        session(['register_user_id' => $user->id]);

        return redirect()->route('register.step2');
    }

    // Step2表示
    public function showStep2()
    {
        return view('register.step2');
    }

    // Step2送信 → 目標体重と現在体重登録 → ログイン画面へ
    public function postStep2(Request $request)
    {
        $request->validate([
            'target_weight' => 'required|numeric|min:1',
            'current_weight' => 'required|numeric|min:1',
        ]);

        $userId = session('register_user_id');

        // 念のためユーザー取得（万一セッション切れに対応）
        $user = User::findOrFail($userId);

        // 目標体重登録
        WeightTarget::create([
            'user_id' => $user->id,
            'target_weight'  => $request->target_weight,
        ]);

        // 現在体重登録（カロリーは初期値0として登録）
WeightLog::create([
    'user_id'  => $user->id,
    'date'     => now()->toDateString(),
    'weight'   => $request->current_weight,
    'calories' => 0,
    'exercise_time' => '00:00:00',        // ← 追加
    'exercise_content' => '',             // ← 追加（nullableなら空文字でOK）
]);

        // セッション削除
        session()->forget('register_user_id');

        // 自動ログイン
        Auth::login($user);

        // 体重管理画面へリダイレクト
        return redirect('/weight_logs');
    }
}