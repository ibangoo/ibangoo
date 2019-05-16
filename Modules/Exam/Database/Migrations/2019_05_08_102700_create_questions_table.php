<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['radio', 'checkbox', 'boolean', 'input', 'textarea'])->comment('类型：radio=单选，checkbox=多选，boolean=判断，input=填空，textarea=简答');
            $table->text('content')->comment('题干');
            $table->text('content_image')->nullable()->comment('题干插图');
            $table->text('options')->nullable()->comment('选项');
            $table->text('explain')->nullable()->comment('试题解析');
            $table->text('explain_image')->nullable()->comment('试题解析插图');
            $table->text('answer')->nullable()->comment('正确答案');
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
        Schema::dropIfExists('questions');
    }
}
