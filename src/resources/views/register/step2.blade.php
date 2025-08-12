<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規会員登録_STEP2</title>
    <link rel="stylesheet" href="{{ asset('css/register_step2.css') }}">
</head>
<body>
    <div class="register-wrapper">
        <div class="form-box">
            <h1 class="logo">PiGLy</h1>
            <h2 class="register-title">新規会員登録</h2>
            <p class="step-info">STEP2 体重データの入力</p>

            <form action="{{ route('register.step2.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="current_weight">現在の体重</label>
                    <div class="input-with-unit">
                        <input
                            type="text"
                            id="current_weight"
                            name="current_weight"
                            placeholder="現在の体重を入力"
                            value="{{ old('current_weight') }}"
                        >
                        <span class="unit">kg</span>
                    </div>
                    @if ($errors->has('current_weight'))
                        <div class="error-message">{{ $errors->first('current_weight') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="target_weight">目標の体重</label>
                    <div class="input-with-unit">
                        <input 
                            type="text" 
                            id="target_weight" 
                            name="target_weight" 
                            placeholder="目標の体重を入力" 
                            value="{{ old('target_weight') }}"
                        >
                        <span class="unit">kg</span>
                    </div>
                    @if ($errors->has('target_weight'))
                        <div class="error-message">{{ $errors->first('target_weight') }}</div>
                    @endif
                </div>

                <button type="submit" class="submit-btn">アカウント作成</button>
            </form>
        </div>
    </div>
</body>
</html>