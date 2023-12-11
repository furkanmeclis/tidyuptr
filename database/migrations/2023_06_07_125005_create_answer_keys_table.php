<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_exam_id');
            $table->string('file');
            $table->timestamps();
            $table->foreign('batch_exam_id')->references('id')->on('batch_exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_keys');
    }
}
