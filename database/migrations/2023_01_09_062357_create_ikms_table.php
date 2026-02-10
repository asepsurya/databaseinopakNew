<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIkmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ikms', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gender');
            $table->string('alamat');
            $table->string('id_provinsi');
            $table->string('id_kota');
            $table->string('id_kecamatan');
            $table->string('id_desa');
            $table->string('rt');
            $table->string('rw');
            $table->string('telp');
            $table->string('jenisProduk');
            $table->string('merk');
            $table->string('tagline')->nullable();
            $table->string('kelebihan');
            $table->string('gramasi');
            $table->text('jenisKemasan')->nullable();
            $table->string('segmentasi')->nullable();
            $table->integer('harga');
            $table->text('varian');
            $table->text('komposisi');
            $table->text('redaksi');
            $table->text('other')->nullable();

            // legalitas usaha
            $table->string('namaUsaha');
            $table->string('noNIB')->nullable();
            $table->string('noISO')->nullable();
            $table->string('noPIRT')->nullable();
            $table->string('noHAKI')->nullable();
            $table->string('noLayakSehat')->nullable();
            $table->string('noHalal')->nullable();
            $table->string('CPPOB')->nullable();
            $table->string('HACCP')->nullable();
            $table->string('legalitasLain')->nullable();
            $table->string('id_Project');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ikms');
    }
}
