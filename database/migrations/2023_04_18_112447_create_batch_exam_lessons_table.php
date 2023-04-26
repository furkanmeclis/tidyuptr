<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchExamLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_exam_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_exam_id');
            $table->unsignedBigInteger('lesson_id');
            $table->timestamps();
            $table->foreign('batch_exam_id')->references('id')->on('batch_exams')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_exam_lessons');
    }
}
