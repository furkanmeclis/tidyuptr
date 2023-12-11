<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_table_id');
            $table->integer('index');
            $table->boolean('is_recess')->default(false)->comment('Molamı');
            $table->integer('duration')->comment('Ders Süresi (Dakika)');
            $table->integer('recess')->comment('Mola Süresi (Dakika)');
            $table->timestamps();
            $table->foreign("day_table_id")->references("id")->on("day_tables")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hour_tables');
    }
}
