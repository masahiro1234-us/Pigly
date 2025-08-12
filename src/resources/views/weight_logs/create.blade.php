@extends('layouts.app')

@section('title','データ追加')

@section('css')
<link rel="stylesheet" href="{{ asset('css/weight_logs.css') }}">
@endsection

@section('content')
<div class="wl-container">
  <section class="wl-card" style="padding:24px">
    <h2 style="margin:0 0 14px; font-size:18px;">データ追加</h2>

    <form method="POST" action="{{ route('weight_logs.store') }}" class="wl-form">
      @csrf
      <div style="display:grid; gap:12px; max-width:460px;">

        <label>日付
          <input type="date" name="date"
                 value="{{ old('date', now()->toDateString()) }}"
                 class="wl-input__field">
          @error('date') <p class="wl-error">{{ $message }}</p> @enderror
        </label>

        <label>体重
          <div class="wl-input-with-unit">
            <input type="number" step="0.1" min="0" max="500" name="weight"
                   value="{{ old('weight') }}" class="wl-input__field" placeholder="例: 55.0">
            <span class="wl-unit">kg</span>
          </div>
          @error('weight') <p class="wl-error">{{ $message }}</p> @enderror
        </label>

        <label>摂取カロリー
          <div class="wl-input-with-unit">
            <input type="number" min="0" name="calories"
                   value="{{ old('calories') }}" class="wl-input__field" placeholder="例: 1800">
            <span class="wl-unit">cal</span>
          </div>
          @error('calories') <p class="wl-error">{{ $message }}</p> @enderror
        </label>

        <label>運動時間（HH:MM）
          <input type="time" name="exercise_time"
                 value="{{ old('exercise_time','00:00') }}" class="wl-input__field">
          @error('exercise_time') <p class="wl-error">{{ $message }}</p> @enderror
        </label>

        <label>運動内容
          <input type="text" name="exercise_content"
                 value="{{ old('exercise_content') }}" class="wl-input__field" placeholder="例: ランニング">
          @error('exercise_content') <p class="wl-error">{{ $message }}</p> @enderror
        </label>

        <button class="wl-btn wl-btn--add" type="submit">保存する</button>
      </div>
    </form>
  </section>
</div>
@endsection