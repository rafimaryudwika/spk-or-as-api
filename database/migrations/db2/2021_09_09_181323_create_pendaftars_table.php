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
            $table->foreignId('fakultas_id');
            $table->foreignId('jurusan_id');
            $table->boolean('daftar_ulang');
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
