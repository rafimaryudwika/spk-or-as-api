<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurusansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id('id_j');
            $table->unsignedBigInteger('id_f');
            $table->unsignedBigInteger('id_bf');
            $table->string('jurusan');
            $table->foreign('id_f')->references('id_f')->on('fakultas');
            $table->foreign('id_bf')->references('id_bf')->on('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurusans');
    }
}
