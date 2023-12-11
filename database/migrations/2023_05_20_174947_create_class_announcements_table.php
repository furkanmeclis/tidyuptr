<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("class_id");
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->longText('content');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->foreign("class_id")->references("id")->on("classes")->onDelete("cascade");
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_announcements');
    }
}
