<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatrimonioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matrimonio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pagina',50);
            $table->string('numeropartida',50);
            $table->string('anio',50);
			$table->date('fechaPartida');

            $table->string('nombreEsposo',64);
            $table->string('apellido1Esposo',64);
            $table->string('apellido2Esposo',64);
            $table->string('edadEsposo',50);
            $table->string('ocupacionEsposo',64);
            $table->string('estadoCivilEsposo',64);
            $table->string('duiEsposo',50);
            $table->string('nacionalidadEsposo',50);
            $table->string('nombrePadreEsposo',50);
            $table->string('apellidoPadreEsposo',50);
            $table->string('nombreMadreEsposo',50);
            $table->string('apellidoMadreEsposo',50);

            
            $table->string('nombreEsposa',64);
            $table->string('apellido1Esposa',64);
            $table->string('apellido2Esposa',64);
            $table->string('edadEsposa',50);
            $table->string('ocupacionEsposa',64);
            $table->string('estadoCivilEsposa',64);
            $table->string('duiEsposa',50);
            $table->string('nacionalidadEsposa',50);
            $table->string('nombrePadreEsposa',50);
            $table->string('apellidoPadreEsposa',50);
            $table->string('nombreMadreEsposa',50);
            $table->string('apellidoMadreEsposa',50);
            
            $table->boolean('apellidoCasada')->default(1);
            $table->string('regimen',50);

            $table->string('nombreTestigo1',50);
            $table->string('apellidoTestigo1',50);
            $table->string('duiTestigo1',50);

            $table->string('nombreTestigo2',50);
            $table->string('apellidoTestigo2',50);
            $table->string('duiTestigo2',50);

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
        Schema::dropIfExists('matrimonio');
    }
}
