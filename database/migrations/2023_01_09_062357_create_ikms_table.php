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
            $table->string('gender')->nullable();;
            $table->string('alamat')->nullable();;
            $table->string('id_provinsi')->nullable();;
            $table->string('id_kota')->nullable();;
            $table->string('id_kecamatan')->nullable();;
            $table->string('id_desa')->nullable();;
            $table->string('rt')->nullable();;
            $table->string('rw')->nullable();;
            $table->text('telp')->nullable();;
            $table->text('jenisProduk')->nullable();;
            $table->text('merk')->nullable();;
            $table->text('tagline')->nullable();
            $table->text('kelebihan')->nullable();;
            $table->text('gramasi')->nullable();;
            $table->text('jenisKemasan')->nullable();
            $table->text('segmentasi')->nullable();
            $table->text('harga')->nullable();;
            $table->text('varian')->nullable();;
            $table->text('komposisi')->nullable();;
            $table->text('redaksi')->nullable();;
            $table->text('other')->nullable();

            // legalitas usaha
            $table->string('namaUsaha')->nullable();;
            $table->string('noNIB')->nullable();
            $table->string('noISO')->nullable();
            $table->string('noPIRT')->nullable();
            $table->string('noHAKI')->nullable();
            $table->string('noLayakSehat')->nullable();
            $table->string('noHalal')->nullable();
            $table->string('CPPOB')->nullable();
            $table->string('HACCP')->nullable();
            $table->string('legalitasLain')->nullable();
            $table->string('id_Project')->nullable();;
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
