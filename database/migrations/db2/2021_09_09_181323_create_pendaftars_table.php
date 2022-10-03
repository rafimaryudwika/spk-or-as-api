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
            $table->id('nim')->unique();
            $table->string('email');
            $table->string('nama');
            $table->string('panggilan');
            $table->foreignId('gender_id');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('alamat_pdg');
            $table->bigInteger('no_hp');
            $table->boolean('daftar_ulang');
            $table->bigInteger('periode');
            $table->foreign('gender_id')->references('id')->on('gender');
            $table->foreign('fakultas_id')->references('id')->on('fakultas');
            $table->foreign('jurusan_id')->references('id')->on('jurusan');
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
