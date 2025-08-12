@extends('layouts.app')

@section('title', 'Weight Log')

@section('css')
<link rel="stylesheet" href="{{ asset('css/weight_logs.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
@endsection

@section('content')
  {{-- /weight_logs と同じフル幅トップバー --}}
  <div class="wl-topnav">
    <div class="wl-topnav__inner">
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
  </div>

  <div class="wl-container">
    <section class="wl-edit-card" id="wl-edit">
      <h2 class="wl-form-title">Weight Log</h2>

      {{-- ★更新フォームに id を付ける（後ろのボタンから submit するため） --}}
      <form id="wl-update-form" method="POST" action="{{ route('weight_logs.update', $log->id) }}" class="wl-form">
        @csrf
        @method('PUT')

        {{-- 日付 --}}
        <div class="wl-field">
          <label for="e_date">日付</label>
          <input id="e_date" type="date" name="date"
                 value="{{ old('date', \Carbon\Carbon::parse($log->date)->format('Y-m-d')) }}"
                 class="wl-input__field">
          @error('date')<p class="wl-error">{{ $message }}</p>@enderror
        </div>

        {{-- 体重 --}}
        <div class="wl-field">
          <label for="e_weight">体重</label>
          <div class="wl-input-with-unit">
            <input id="e_weight" type="number" step="0.1" name="weight"
                   value="{{ old('weight', number_format($log->weight,1,'.','')) }}"
                   placeholder="50.0" class="wl-input__field">
            <span class="wl-unit">kg</span>
          </div>
          @foreach ($errors->get('weight') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>

        {{-- 摂取カロリー --}}
        <div class="wl-field">
          <label for="e_cal">摂取カロリー</label>
          <div class="wl-input-with-unit">
            <input id="e_cal" type="number" name="calories"
                   value="{{ old('calories', $log->calories) }}"
                   placeholder="1200" class="wl-input__field">
            <span class="wl-unit">cal</span>
          </div>
          @foreach ($errors->get('calories') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>

        {{-- 運動時間 --}}
        <div class="wl-field">
          <label for="e_time">運動時間</label>
          <input id="e_time" type="time" name="exercise_time"
                 value="{{ old('exercise_time', \Carbon\Carbon::parse($log->exercise_time)->format('H:i')) }}"
                 placeholder="00:00" class="wl-input__field">
          @error('exercise_time')<p class="wl-error">{{ $message }}</p>@enderror
        </div>

        {{-- 運動内容 --}}
        <div class="wl-field">
          <label for="e_note">運動内容</label>
          <textarea id="e_note" name="exercise_content" rows="6"
                    placeholder="運動内容を追加"
                    class="wl-input__field">{{ old('exercise_content', $log->exercise_content) }}</textarea>
          @foreach ($errors->get('exercise_content') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>
      </form>

      {{-- ★アクション行（中央：戻る・更新／右端：削除）。更新ボタンは form="wl-update-form" で送信 --}}
      <div class="wl-form-actions" id="wl-edit-actions">
        <div class="wl-actions-center">
          <a href="{{ route('weight_logs.index') }}" class="wl-btn wl-btn--ghost">戻る</a>
          <button type="submit" form="wl-update-form" class="wl-btn wl-btn--add">更新</button>
        </div>

        <form method="POST" action="{{ route('weight_logs.destroy', $log->id) }}" class="wl-trash-form" id="wl-edit-trash">
          @csrf
          @method('DELETE')
          <button type="submit" class="wl-btn--trash" title="削除"
                  onclick="return confirm('このデータを削除しますか？')">
            <svg width="21" height="24" viewBox="0 0 24 24" aria-hidden="true">
              <path fill="#F65A5A" d="M9 3h6l1 2h4v2H4V5h4l1-2zm1 6h2v10h-2V9zm4 0h2v10h-2V9zM7 9h2v10H7V9z"/>
            </svg>
          </button>
        </form>
      </div>
    </section>
  </div>

  {{-- 入力が空なら薄グレー表示にする --}}
  <script>
  (() => {
    const wrap = document.getElementById('wl-edit');
    if (!wrap) return;
    const mark = el => el.classList.toggle('is-empty', !el.value);
    wrap.querySelectorAll('.wl-input__field').forEach(el => {
      mark(el);
      el.addEventListener('input', () => mark(el));
      el.addEventListener('change', () => mark(el));
    });
  })();
  </script>
@endsection