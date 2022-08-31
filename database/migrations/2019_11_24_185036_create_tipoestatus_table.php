<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoestatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipoestatus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 64);
            $table->string('descripcion', 128)->nullable();
        });
        DB::table('tipoestatus')->insert(array('id' => 1, 'nombre' => 'Sin Iniciar', 'descripcion' => 'No se ha comenzado'));
        DB::table('tipoestatus')->insert(array('id' => 2, 'nombre' => 'En Proceso', 'descripcion' => 'Actualmente se esta trabajando'));
        DB::table('tipoestatus')->insert(array('id' => 3, 'nombre' => 'Finalizado', 'descripcion' => 'Ejecución terminada'));
        DB::table('tipoestatus')->insert(array('id' => 4, 'nombre' => 'Diferido', 'descripcion' => 'Aplazado'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipoestatus');
    }
}
