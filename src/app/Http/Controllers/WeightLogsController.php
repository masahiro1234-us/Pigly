<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightLog;
use App\Models\WeightTarget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as LaravelValidator;

class WeightLogsController extends Controller
{
    // 一覧
    public function index()
    {
        $user = Auth::user();

        $targetWeight = WeightTarget::where('user_id', $user->id)->value('target_weight');
        $latestWeight  = WeightLog::where('user_id', $user->id)->orderByDesc('date')->value('weight');

        $logs = WeightLog::where('user_id', $user->id)
            ->orderByDesc('date')
            ->paginate(8);

        return view('weight_logs.index', compact('targetWeight', 'latestWeight', 'logs'));
    }

    // 検索
    public function search(Request $request)
    {
        $user = Auth::user();

        $q = WeightLog::where('user_id', $user->id);

        if ($request->filled('from')) {
            $q->whereDate('date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $q->whereDate('date', '<=', $request->input('to'));
        }

        $logs = $q->orderByDesc('date')
            ->paginate(8)
            ->appends($request->only('from', 'to'));

        $targetWeight = WeightTarget::where('user_id', $user->id)->value('target_weight');
        $latestWeight = WeightLog::where('user_id', $user->id)->orderByDesc('date')->value('weight');

        return view('weight_logs.index', compact('targetWeight', 'latestWeight', 'logs'));
    }

    // 追加フォーム（モーダル運用でも念のため残す）
    public function create()
    {
        return view('weight_logs.create');
    }

    // 追加処理
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        $this->attachSpecMessages($validator);

        if ($validator->fails()) {
            // _from=create も old() に残るようそのまま返す
            return back()->withErrors($validator)->withInput($request->all());
        }

        $data = $validator->validated();
        // TIME型想定：秒を付与
        $data['exercise_time'] = $data['exercise_time'] . ':00';
        $data['user_id'] = Auth::id();

        WeightLog::create($data);

        return redirect()->route('weight_logs.index')->with('success', 'データを追加しました。');
    }

    // 詳細（編集フォーム表示）
    public function show($weightLogId)
    {
        $user = Auth::user();

        $log = WeightLog::where('user_id', $user->id)
            ->where('id', $weightLogId)
            ->firstOrFail();

        return view('weight_logs.edit', compact('log'));
    }

    // 更新
    public function update(Request $request, $weightLogId)
    {
        $user = Auth::user();

        $log = WeightLog::where('user_id', $user->id)
            ->where('id', $weightLogId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        $this->attachSpecMessages($validator);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['exercise_time'] = $data['exercise_time'] . ':00';

        $log->update($data);

        return redirect()->route('weight_logs.index')->with('success', '更新しました。');
    }

    // 削除
    public function destroy($weightLogId)
    {
        $user = Auth::user();

        $log = WeightLog::where('user_id', $user->id)
            ->where('id', $weightLogId)
            ->firstOrFail();

        $log->delete();

        return redirect()->route('weight_logs.index')->with('success', '削除しました。');
    }

    /** 入力ルール（見本仕様） */
    private function rules(): array
    {
        return [
            // 未入力時は required だけ出す（重複防止）
            'date'             => ['bail', 'required', 'date'],

            // 4桁まで + 小数1桁（例: 50 / 50.0）
            'weight'           => ['required', 'numeric', 'gte:0', 'lte:9999.9', 'regex:/^\d{1,4}(\.\d)?$/'],

            'calories'         => ['required', 'integer', 'min:0'],

            // 未入力時は required のみ。入力があって形式が違う時だけフォーマットエラー
            'exercise_time'    => ['bail', 'required', 'date_format:H:i'],

            'exercise_content' => ['nullable', 'string', 'max:120'],
        ];
    }

    /** 日本語メッセージ（基本） */
    private function messages(): array
    {
        return [
            'date.required'              => '日付を入力してください',
            'date.date'                  => '日付を入力してください', // 未入力以外の date 失敗も同文に統一

            'weight.required'            => '体重を入力してください',
            'weight.numeric'             => '数字で入力してください',
            'weight.lte'                 => '4桁までの数字で入力してください',
            'weight.regex'               => '小数点は1桁で入力してください',

            'calories.required'          => '摂取カロリーを入力してください',
            'calories.integer'           => '数字で入力してください',

            'exercise_time.required'     => '運動時間を入力してください',
            'exercise_time.date_format'  => '運動時間を入力してください', // 見本に合わせて同文で出す

            'exercise_content.max'       => '120文字以内で入力してください',
        ];
    }

    /**
     * 見本どおりに「体重/カロリー」だけ複数行の注意文を並べる。
     * 日付・運動時間には追加しない（重複の原因になるため）。
     */
    private function attachSpecMessages(LaravelValidator $validator): void
    {
        $validator->after(function (LaravelValidator $v) {
            $errors = $v->errors();

            if ($errors->has('weight')) {
                foreach (['4桁までの数字で入力してください', '小数点は1桁で入力してください'] as $msg) {
                    if (!in_array($msg, $errors->get('weight'), true)) {
                        $errors->add('weight', $msg);
                    }
                }
            }

            if ($errors->has('calories')) {
                $msg = '数字で入力してください';
                if (!in_array($msg, $errors->get('calories'), true)) {
                    $errors->add('calories', $msg);
                }
            }
        });
    }
}