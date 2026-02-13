<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename id_ikm to id_Ikm in cots table
        Schema::table('cots', function (Blueprint $table) {
            $table->renameColumn('id_ikm', 'id_Ikm');
        });
    }

    public function down(): void
    {
        // Reverse the change
        Schema::table('cots', function (Blueprint $table) {
            $table->renameColumn('id_Ikm', 'id_ikm');
        });
    }
};
