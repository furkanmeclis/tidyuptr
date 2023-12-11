<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('time_table_id');
            $table->integer('day')->comment('0:Pazartesi, 1:Salı, 2:Çarşamba, 3:Perşembe, 4:Cuma, 5:Cumartesi, 6:Pazar');
            $table->time('start_time')->comment('Dersin Başlangıç Saati');
            $table->timestamps();
            $table->foreign("time_table_id")->references("id")->on("time_tables")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_tables');
    }
}
