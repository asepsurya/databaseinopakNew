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
            $table->string('id_Ikm');
            $table->string('id_Project');
            $table->text('sejarahSingkat')->nullable();
            $table->text('produkjual')->nullable();
            $table->text('carapemasaran')->nullable();
            $table->text('bahanbaku')->nullable();
            $table->text('prosesproduksi')->nullable();
            $table->text('omset')->nullable();
            $table->text('kapasitasProduksi')->nullable();
            $table->text('kendala')->nullable();
            $table->text('solusi')->nullable();
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
