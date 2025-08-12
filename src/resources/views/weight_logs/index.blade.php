@extends('layouts.app')

@section('title', '体重管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/weight_logs.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&display=swap" rel="stylesheet">
@endsection

@section('content')
  {{-- ページ専用“フル幅”トップバー --}}
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
    {{-- ステータスカード --}}
    <section class="wl-stats">
      <div class="wl-stat">
        <p class="wl-stat__label">目標体重</p>
        <p class="wl-stat__value">{{ number_format($targetWeight ?? 0,1) }}<span>kg</span></p>
      </div>

      <div class="wl-divider"></div>

      <div class="wl-stat">
        <p class="wl-stat__label">目標まで</p>
        <p class="wl-stat__value">{{ number_format(($latestWeight ?? 0) - ($targetWeight ?? 0),1) }}<span>kg</span></p>
      </div>

      <div class="wl-divider"></div>

      <div class="wl-stat">
        <p class="wl-stat__label">最新体重</p>
        <p class="wl-stat__value">{{ number_format($latestWeight ?? 0,1) }}<span>kg</span></p>
      </div>
    </section>

    {{-- 検索バー＋範囲/件数＋一覧を1カードに統合 --}}
    <section id="wl-index" class="wl-card wl-card--results">
      <div class="wl-card__filters">
        <form method="GET" action="{{ route('weight_logs.search') }}" class="wl-card__filter-left">
          <label class="wl-input wl-input--date">
            <input type="date" name="from" value="{{ request('from') }}">
          </label>
          <span class="wl-tilde">~</span>
          <label class="wl-input wl-input--date">
            <input type="date" name="to" value="{{ request('to') }}">
          </label>

          <button class="wl-btn wl-btn--search" type="submit">検索</button>

          @if (request()->filled('from') || request()->filled('to'))
            <a href="{{ route('weight_logs.index') }}" class="wl-btn wl-btn--reset">リセット</a>
          @endif
        </form>

        <a href="{{ route('weight_logs.create') }}" id="wl-open-create" class="wl-btn wl-btn--add">データ追加</a>
      </div>

      {{-- 検索範囲＋件数表示 --}}
      @if (request()->filled('from') || request()->filled('to'))
        @php
          $from = request('from');
          $to   = request('to');
          $fmt  = fn($d) => \Carbon\Carbon::parse($d)->format('Y年n月j日');
          $rangeText = $from && $to
            ? $fmt($from).'〜'.$fmt($to)
            : ($from ? $fmt($from).'以降' : ($to ? $fmt($to).'まで' : ''));
        @endphp
        <p class="wl-search-info">{{ $rangeText }} の検索結果 {{ $logs->total() }}件</p>
      @endif

      {{-- 一覧テーブル --}}
      <div class="wl-table">
        <div class="wl-thead">
          <div class="wl-th w-date">日付</div>
          <div class="wl-th w-weight">体重</div>
          <div class="wl-th w-cal">食事摂取カロリー</div>
          <div class="wl-th w-time">運動時間</div>
          <div class="w-edit"></div>
        </div>

        @forelse ($logs as $log)
          <div class="wl-row">
            <div class="wl-td w-date">{{ \Carbon\Carbon::parse($log->date)->format('Y/m/d') }}</div>
            <div class="wl-td w-weight">{{ rtrim(rtrim(number_format($log->weight,1),'0'),'.') }}kg</div>
            <div class="wl-td w-cal">{{ $log->calories }}cal</div>
            <div class="wl-td w-time">{{ \Carbon\Carbon::parse($log->exercise_time)->format('H:i') }}</div>
            <div class="wl-td w-edit">
              <a href="{{ route('weight_logs.show', $log->id) }}" class="wl-pencil" aria-label="編集">
                <svg viewBox="0 0 24 24" class="wl-pencil__svg" aria-hidden="true">
                  <defs>
                    <linearGradient id="g" x1="0" x2="1">
                      <stop offset="0%" stop-color="#A3A6E1"/>
                      <stop offset="100%" stop-color="#FFABD9"/>
                    </linearGradient>
                  </defs>
                  <path fill="url(#g)" d="M3 17.25V21h3.75l11-11-3.75-3.75-11 11zM20.71 7.04a1.001 1.001 0 0 0 0-1.41l-2.34-2.34a1.001 1.001 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
              </a>
            </div>
          </div>
        @empty
          <div class="wl-empty">データがありません</div>
        @endforelse
      </div>

      {{-- ページネーション（8件/ページ想定） --}}
      @if(method_exists($logs,'links'))
        <div class="wl-pagination">
          {{ $logs->onEachSide(1)->links('pagination::default') }}
        </div>
      @endif
    </section>
  </div>

{{-- ===== Create Modal ===== --}}
<div class="wl-modal" id="wl-create-modal" aria-hidden="true" role="dialog" aria-modal="true">
  <div class="wl-modal__backdrop" data-close></div>

  <div class="wl-modal__card" role="document" aria-labelledby="wl-create-title">
    <h3 class="wl-modal__title" id="wl-create-title">Weight Logを追加</h3>

    <form method="POST" action="{{ route('weight_logs.store') }}" class="wl-form" id="wl-create-form">
      @csrf
      <input type="hidden" name="_from" value="create">

      {{-- 入力エリア --}}
      <div class="wl-modal__body">
        {{-- 日付 --}}
        <div class="wl-field">
          <label for="c_date">日付 <span class="wl-req">必須</span></label>
          <input id="c_date" type="date" name="date"
                 value="{{ old('date') }}"
                 class="wl-input__field">
          @if ($errors->has('date'))
            <p class="wl-error">{{ $errors->first('date') }}</p>
          @endif
        </div>

        {{-- 体重 --}}
        <div class="wl-field">
          <label for="c_weight">体重 <span class="wl-req">必須</span></label>
          <div class="wl-input-with-unit">
            <input id="c_weight" type="number" step="0.1" name="weight"
                   value="{{ old('weight') }}" placeholder="50.0"
                   class="wl-input__field">
            <span class="wl-unit">kg</span>
          </div>
          @foreach ($errors->get('weight') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>

        {{-- 摂取カロリー --}}
        <div class="wl-field">
          <label for="c_cal">摂取カロリー <span class="wl-req">必須</span></label>
          <div class="wl-input-with-unit">
            <input id="c_cal" type="number" name="calories"
                   value="{{ old('calories') }}" placeholder="1200"
                   class="wl-input__field">
            <span class="wl-unit">cal</span>
          </div>
          @foreach ($errors->get('calories') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>

        {{-- 運動時間 --}}
        <div class="wl-field">
          <label for="c_time">運動時間 <span class="wl-req">必須</span></label>
          <input id="c_time" type="time" name="exercise_time"
                 value="{{ old('exercise_time') }}"
                 placeholder="00:00"
                 class="wl-input__field">
          @if ($errors->has('exercise_time'))
            <p class="wl-error">{{ $errors->first('exercise_time') }}</p>
          @endif
        </div>

        {{-- 運動内容 --}}
        <div class="wl-field">
          <label for="c_note">運動内容</label>
          <textarea id="c_note" name="exercise_content" rows="4"
                    placeholder="運動内容を追加"
                    class="wl-input__field">{{ old('exercise_content') }}</textarea>
          @foreach ($errors->get('exercise_content') as $message)
            <p class="wl-error">{{ $message }}</p>
          @endforeach
        </div>
      </div>{{-- /.wl-modal__body --}}

      {{-- ボタン行（中央寄せ） --}}
      <div class="wl-modal__actions">
        <button type="button" class="wl-btn wl-btn--ghost" data-close>戻る</button>
        <button type="submit" class="wl-btn wl-btn--add">登録</button>
      </div>
    </form>
  </div>
</div>

  <script>
  (() => {
    const openBtn  = document.getElementById('wl-open-create');
    const modal    = document.getElementById('wl-create-modal');
    const backdrop = modal?.querySelector('.wl-modal__backdrop');
    const closeEls = modal?.querySelectorAll('[data-close]');

    const mark = el => el.classList.toggle('is-empty', !el.value);

    const initToggle = () => {
      if (!modal) return;
      modal.querySelectorAll('.wl-input__field').forEach(el => {
        mark(el);
        if (!el.dataset.bound) {
          el.addEventListener('input',  () => mark(el));
          el.addEventListener('change', () => mark(el));
          el.dataset.bound = '1';
        }
      });
    };

    const open = (e) => {
      if (e) e.preventDefault();
      modal.classList.add('is-open');
      document.documentElement.style.overflow = 'hidden';
      initToggle();
    };

    const close = () => {
      modal.classList.remove('is-open');
      document.documentElement.style.overflow = '';
    };

    openBtn?.addEventListener('click', open);
    backdrop?.addEventListener('click', close);
    closeEls?.forEach(el => el.addEventListener('click', close));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
    });

    @if ($errors->any() && old('_from') === 'create')
      open();
    @endif
  })();
  </script>
@endsection