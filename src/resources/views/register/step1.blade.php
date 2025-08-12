@extends('layouts.app')

{{-- タイトル --}}
@section('title', '新規会員登録')

{{-- ページ固有CSSは stack に積む --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/register_step1.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
@endpush

{{-- 中身 --}}
@section('content')
<div class="register-bg">
    <div class="register-wrapper">
        <h1 class="logo">PiGLy</h1>
        <h2 class="heading">新規会員登録</h2>
        <p class="step-text">STEP1 アカウント情報の登録</p>

        <form action="{{ route('register.step1.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">お名前</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="名前を入力">
                @foreach ($errors->get('name') as $message)
                    <p class="error-message">{{ $message }}</p>
                @endforeach
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="メールアドレスを入力">
                @foreach ($errors->get('email') as $message)
                    <p class="error-message">{{ $message }}</p>
                @endforeach
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" placeholder="パスワードを入力">
                @foreach ($errors->get('password') as $message)
                    <p class="error-message">{{ $message }}</p>
                @endforeach
            </div>

            <div class="btn-wrapper">
                <button type="submit" class="next-btn">次に進む</button>
            </div>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection