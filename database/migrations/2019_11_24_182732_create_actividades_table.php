<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idProyecto')->unsigned();
            $table->integer('idEstatus')->unsigned();
            $table->string('nombre',128);
            $table->string('descripcion',256)->nullable();
            $table->integer('avance');
            $table->decimal('costoEstimado',12,2);
            $table->decimal('costoReal', 12, 2)->nullable();
            $table->date('inicio');
            $table->date('fin');

            $table->foreign('idProyecto')->references('id')->on('proyecto');
            $table->foreign('idEstatus')->references('id')->on('tipoestatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}
