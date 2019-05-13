<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_papers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('所属用户');
            $table->unsignedBigInteger('test_id')->comment('所属测试');
            $table->unsignedInteger('minutes')->comment('测试时间');
            $table->unsignedInteger('total_score')->comment('测试总分');
            $table->unsignedInteger('actual_score')->comment('测试实际分数');
            $table->enum('status', ['fail', 'pass', 'credit', 'distinction', 'high_distinction'])->default('fail')->comment('状态：fail=不及格；pass=及格；credit=中等；distinction=良好；high_distinction=优秀');
            $table->boolean('is_judge')->default(true)->comment('是否已经判卷');
            $table->json('answers')->comment('用户答题');
            $table->json('content')->comment('测试试卷内容');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**s
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_papers');
    }
}
