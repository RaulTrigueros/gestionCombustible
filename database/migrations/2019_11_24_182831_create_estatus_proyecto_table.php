<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstatusProyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estatus_proyecto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idProyecto')->unsigned();
            $table->integer('idTipoEstatus')->unsigned();
            $table->boolean('actual')->default(0);
            $table->date('fecha');
            $table->time('hora');

            $table->foreign('idProyecto')->references('id')->on('proyecto');
            $table->foreign('idTipoEstatus')->references('id')->on('tipoestatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estatus_proyecto');
    }
}
