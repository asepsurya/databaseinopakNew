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
        // Rename id_ikm to id_Ikm in dokumentasi_cots table for consistency
        Schema::table('dokumentasi_cots', function (Blueprint $table) {
            $table->renameColumn('id_ikm', 'id_Ikm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the rename
        Schema::table('dokumentasi_cots', function (Blueprint $table) {
            $table->renameColumn('id_Ikm', 'id_ikm');
        });
    }
};
