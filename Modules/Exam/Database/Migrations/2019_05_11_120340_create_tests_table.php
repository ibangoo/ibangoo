<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('测试名称');
            $table->unsignedMediumInteger('total_score')->comment('测试总分');
            $table->json('options')->comment('测试选项');
            $table->enum('mode', ['tag', 'question'])->comment('测试出题类型：tag=标签出题、question=题库选题');
            $table->boolean('status')->default(false)->comment('是否禁用');
            $table->boolean('is_auto')->default(true)->comment('是否系统判断');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
