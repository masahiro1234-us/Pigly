<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightLogsTable extends Migration
{
    public function up()
    {
        Schema::create('weight_logs', function (Blueprint $table) {
            $table->id();  // bigint unsigned PK
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');  // 日付
            $table->decimal('weight', 4, 1);  // 体重
            $table->integer('calories')->nullable(0);  // 摂取カロリー
            $table->time('exercise_time');  // 運動時間
            $table->text('exercise_content')->nullable(); // 運動内容（空でもOK）
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('weight_logs');
    }
}
