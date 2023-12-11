<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("organization_id");
            $table->string("name");
            $table->unsignedBigInteger("teacher_id")->nullable();
            $table->timestamps();
            $table->foreign("organization_id")->references("id")->on("organizations")->onDelete("cascade");
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
        Schema::dropIfExists('classes');
    }
}
