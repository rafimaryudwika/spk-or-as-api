<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->timestamp('tgl_daftar');
            $table->id('nim');
            $table->string('email');
            $table->string('nama');
            $table->string('panggilan');
            $table->integer('id_g');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('alamat_pdg');
            $table->bigInteger('no_hp');
            $table->integer('id_j');
            $table->integer('id_f');
            $table->tinyInteger('daftar_ulang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftars');
    }
}
