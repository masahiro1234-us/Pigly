<?php

use App\Http\Controllers\WeightLogsController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    // 一覧（管理画面トップ）
    Route::get('/weight_logs', [WeightLogsController::class, 'index'])->name('weight_logs.index');

    // ---- 固定パス（{id} より上）----
    Route::get('/weight_logs/search', [WeightLogsController::class, 'search'])->name('weight_logs.search');
    Route::get('/weight_logs/create', [WeightLogsController::class, 'create'])->name('weight_logs.create');
    Route::post('/weight_logs', [WeightLogsController::class, 'store'])->name('weight_logs.store');

    // 目標体重設定
    Route::get('/weight_logs/goal_setting', [GoalController::class, 'edit'])->name('goal.edit');
    Route::post('/weight_logs/goal_setting', [GoalController::class, 'update'])->name('goal.update');

    // ---- 更新/削除系（{id} の可変よりも上に置く）----
    // 更新（既存互換：POST /{id}/update を維持）
    Route::post('/weight_logs/{weightLogId}/update', [WeightLogsController::class, 'update'])
        ->name('weight_logs.update');

    // 削除（★新規：Blade が参照している weight_logs.destroy を正式に用意）
    Route::delete('/weight_logs/{weightLogId}', [WeightLogsController::class, 'destroy'])
        ->name('weight_logs.destroy');

    // 互換のため旧・POST削除ルートも残す（使っていなければ後で削除OK）
    Route::post('/weight_logs/{weightLogId}/delete', [WeightLogsController::class, 'destroy'])
        ->name('weight_logs.delete');

    // ---- 可変パス（最後に置く）----
    Route::get('/weight_logs/{weightLogId}', [WeightLogsController::class, 'show'])->name('weight_logs.show');

    // ログアウト
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

// ログイン画面表示
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// ログイン処理
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// 会員登録ステップ1
Route::get('/register/step1', [RegisterController::class, 'showStep1'])->name('register.step1');
Route::post('/register/step1', [RegisterController::class, 'postStep1'])->name('register.step1.post');

// 会員登録ステップ2
Route::get('/register/step2', [RegisterController::class, 'showStep2'])->name('register.step2');
Route::post('/register/step2', [RegisterController::class, 'postStep2'])->name('register.step2.post');