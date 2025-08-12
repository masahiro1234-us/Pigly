<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightTarget extends Model
{
    use HasFactory;

    // テーブル名は規約通り weight_targets。$table の指定は不要

    protected $fillable = [
        'user_id',
        'target_weight',
    ];

    // 小数1桁として扱う（DBは decimal(4,1)）
    protected $casts = [
        'target_weight' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}