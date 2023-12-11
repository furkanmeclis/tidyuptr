<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hour_table_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->boolean('is_live')->default(false);
            $table->timestamps();
            $table->foreign("lesson_id")->references("id")->on("lessons")->onDelete("cascade");
            $table->foreign("teacher_id")->references("id")->on("teachers")->onDelete("cascade");
            $table->foreign("hour_table_id")->references("id")->on("hour_tables")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_hours');
    }
}
