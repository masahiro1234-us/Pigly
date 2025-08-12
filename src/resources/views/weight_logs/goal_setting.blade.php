@extends('layouts.app')

@section('title','目標体重設定')

@section('css')
<link rel="stylesheet" href="{{ asset('css/weight_logs.css') }}">
@endsection

{{-- weight_logs と同じヘッダー（共通は触らない） --}}
@section('header-link')
  <div class="wl-topnav">
    <div class="wl-brand">PiGLy</div>
    <div class="wl-actions">
      <a href="{{ route('goal.edit') }}" class="wl-btn wl-btn--ghost">
        <span class="wl-btn__icon">⚙️</span> 目標体重設定
      </a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="wl-btn wl-btn--ghost">ログアウト</button>
      </form>
    </div>
  </div>
@endsection

@section('content')
<div class="goal-wrap">
  <section class="goal-card">
    <h2 class="goal-title">目標体重設定</h2>

    <form method="POST" action="{{ route('goal.update') }}">
      @csrf

      <div class="goal-input-row">
        <input
          type="number"
          name="target_weight"
          step="0.1"
          value="{{ old('target_weight', $targetWeight) }}"
          class="goal-input"
          placeholder="例) 46.5"
          inputmode="decimal">
        <span class="goal-kg">kg</span>
      </div>
      @error('target_weight')
        <p class="goal-error">{{ $message }}</p>
      @enderror

      <div class="goal-actions">
        <a href="{{ route('weight_logs.index') }}" class="wl-btn wl-btn--ghost">戻る</a>
        <button type="submit" class="wl-btn wl-btn--add goal-update">更新</button>
      </div>
    </form>
  </section>
</div>
@endsection