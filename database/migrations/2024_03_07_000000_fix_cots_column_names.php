<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if columns with old names exist and rename them
        if (Schema::hasColumn('cots', 'id_ikm')) {
            Schema::table('cots', function (Blueprint $table) {
                $table->renameColumn('id_ikm', 'id_Ikm');
            });
        }

        if (Schema::hasColumn('cots', 'id_project')) {
            Schema::table('cots', function (Blueprint $table) {
                $table->renameColumn('id_project', 'id_Project');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('cots', 'id_Ikm')) {
            Schema::table('cots', function (Blueprint $table) {
                $table->renameColumn('id_Ikm', 'id_ikm');
            });
        }

        if (Schema::hasColumn('cots', 'id_Project')) {
            Schema::table('cots', function (Blueprint $table) {
                $table->renameColumn('id_Project', 'id_project');
            });
        }
    }
};
