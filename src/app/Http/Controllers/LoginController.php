<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '正しいメールアドレスの形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは6文字以上で入力してください',
        ]);

        // 認証試行
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // 認証成功
            return redirect()->route('weight_logs.index');
        }

        // 認証失敗
        return back()->withErrors([
            'login' => 'メールアドレスまたはパスワードが正しくありません',
        ])->withInput();
    }
}