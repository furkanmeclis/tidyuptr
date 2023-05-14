<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("question_id");
            $table->unsignedBigInteger("teacher_id");
            $table->unsignedBigInteger("student_id");
            $table->boolean("is_teacher")->default(false);
            $table->text("answer");
            $table->text('file')->nullable();
            $table->timestamps();
            $table->foreign("question_id")->references("id")->on("questions")->onDelete("cascade");
            $table->foreign("teacher_id")->references("id")->on("teachers")->onDelete("cascade");
            $table->foreign("student_id")->references("id")->on("students")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
