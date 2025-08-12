<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    use HasFactory;

    protected $table = 'weight_logs';

    protected $fillable = [
        'user_id',
        'date',
        'weight',
        'calories',
        'exercise_time',
        'exercise_content',
    ];

    protected $casts = [
        'date' => 'date',
        'weight' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}