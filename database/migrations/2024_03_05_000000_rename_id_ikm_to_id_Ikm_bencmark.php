<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename id_ikm to id_Ikm in bencmark_produks table for consistency
        Schema::table('bencmark_produks', function (Blueprint $table) {
            $table->renameColumn('id_ikm', 'id_Ikm');
        });

        // Also rename in produk_designs table if needed
        Schema::table('produk_designs', function (Blueprint $table) {
            $table->renameColumn('id_ikm', 'id_Ikm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the rename
        Schema::table('bencmark_produks', function (Blueprint $table) {
            $table->renameColumn('id_Ikm', 'id_ikm');
        });

        Schema::table('produk_designs', function (Blueprint $table) {
            $table->renameColumn('id_Ikm', 'id_ikm');
        });
    }
};
