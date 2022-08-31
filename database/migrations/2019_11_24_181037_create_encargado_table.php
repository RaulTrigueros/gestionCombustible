<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncargadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encargado', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 128);
            $table->string('nit',32);
            $table->string('telefono',16)->nullable();
            $table->string('direccion',256)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encargado');
    }
}
