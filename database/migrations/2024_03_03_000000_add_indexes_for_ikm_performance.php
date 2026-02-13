<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesForIkmPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add indexes to ikms table for better query performance
        Schema::table('ikms', function (Blueprint $table) {
            $table->index('id_Project');
            $table->index('id_provinsi');
            $table->index('id_kota');
            $table->index('id_kecamatan');
            $table->index('id_desa');
        });

        // Add indexes to cots table
        Schema::table('cots', function (Blueprint $table) {
            $table->index('id_ikm');
            $table->index('id_project');
        });

        // Add indexes to dokumentasi_cots table
        Schema::table('dokumentasi_cots', function (Blueprint $table) {
            $table->index('id_ikm');
            $table->index('id_project');
        });

        // Add indexes to bencmark_produks table
        Schema::table('bencmark_produks', function (Blueprint $table) {
            $table->index('id_ikm');
            $table->index('id_project');
        });

        // Add indexes to produk_designs table
        Schema::table('produk_designs', function (Blueprint $table) {
            $table->index('id_ikm');
            $table->index('id_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ikms', function (Blueprint $table) {
            $table->dropIndex(['id_Project']);
            $table->dropIndex(['id_provinsi']);
            $table->dropIndex(['id_kota']);
            $table->dropIndex(['id_kecamatan']);
            $table->dropIndex(['id_desa']);
        });

        Schema::table('cots', function (Blueprint $table) {
            $table->dropIndex(['id_ikm']);
            $table->dropIndex(['id_project']);
        });

        Schema::table('dokumentasi_cots', function (Blueprint $table) {
            $table->dropIndex(['id_ikm']);
            $table->dropIndex(['id_project']);
        });

        Schema::table('bencmark_produks', function (Blueprint $table) {
            $table->dropIndex(['id_ikm']);
            $table->dropIndex(['id_project']);
        });

        Schema::table('produk_designs', function (Blueprint $table) {
            $table->dropIndex(['id_ikm']);
            $table->dropIndex(['id_project']);
        });
    }
}
