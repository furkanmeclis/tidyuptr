<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("teacher_id");
            $table->text("question");
            $table->boolean("is_answered")->default(false);
            $table->text('file')->nullable();
            $table->timestamps();
            $table->foreign("student_id")->references("id")->on("students")->onDelete("cascade");
            $table->foreign("teacher_id")->references("id")->on("teachers")->onDelete("cascade");

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
