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
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->timestamp('tgl_daftar');
            $table->id('nim')->unique();
            $table->string('email')->unique();
            $table->string('nama');
            $table->string('panggilan');
            $table->foreignId('id_g')->constrained('gender');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->foreignId('id_j')->constrained('jurusan');
            $table->string('alamat_pdg');
            $table->bigInteger('no_hp')->unique();
            $table->bigInteger('periode');
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
