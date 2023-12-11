<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpticalParameterDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optical_paramater_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('optical_paramater_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->string('type');
            $table->string('name');
            $table->string('index');
            $table->string('length');
            $table->json('coordinates');
            $table->string('alignment');
            $table->string('data_type');
            $table->timestamps();
            $table->foreign('optical_paramater_id')->references('id')->on('optical_paramaters')->onDelete('cascade');
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
        Schema::dropIfExists('optical_paramaters');
    }
}
