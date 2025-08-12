<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Validation\Factory as ValidationFactory;

class RegisterStep1Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // バリデーションルール
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email'], 
            'password' => 'required',
        ];
    }

    // エラーメッセージ
    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }

    // すべてのバリデーションエラーを表示する設定
    public function validator(ValidationFactory $factory): ValidatorContract
    {
        return $factory->make($this->all(), $this->rules(), $this->messages())
            ->stopOnFirstFailure(false);
    }
}