<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cots', function (Blueprint $table) {
            $table->id();
            $table->string('id_ikm');
            $table->string('id_project');
            $table->text('sejarahSingkat');
            $table->text('produkjual');
            $table->text('carapemasaran');
            $table->text('bahanbaku');
            $table->text('prosesproduksi');
            $table->text('omset');
            $table->text('kapasitasProduksi');
            $table->text('kendala');
            $table->text('solusi');
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
        Schema::dropIfExists('cots');
    }
}
