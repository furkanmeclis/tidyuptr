<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('answer_key_id');
            $table->unsignedBigInteger('lesson_id');
            $table->integer('question_number');
            $table->string('answer_value');
            $table->string('b_number');
            $table->text('topic')->nullable();
            $table->timestamps();
            $table->foreign('answer_key_id')->references('id')->on('answer_keys')->onDelete('cascade');
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
        Schema::dropIfExists('answer_values');
    }
}
