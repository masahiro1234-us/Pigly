<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン | PiGLy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <h1 class="title">PiGLy</h1>
            <h2 class="subtitle">ログイン</h2>

            {{-- 成功メッセージ --}}
            @if (session('success'))
                <div class="flash-message">
                    {{ session('success') }}
                </div>
            @endif

            {{-- バリデーションエラー表示 --}}
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ログインフォーム --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" placeholder="メールアドレスを入力" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" placeholder="パスワードを入力" required>
                </div>

                <button type="submit" class="login-btn">ログイン</button>
            </form>

            <div class="register-link">
                <a href="{{ route('register.step1') }}">アカウント作成はこちら</a>
            </div>
        </div>
    </div>
</body>
</html>