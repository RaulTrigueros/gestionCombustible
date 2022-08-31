<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscripcionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idprograma')->unsigned();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('sexo');
            $table->integer('edad');
            $table->string('dui');
            $table->string('comunidad');
            $table->string('correo')->nullable();
            $table->string('telefono', 12)->nullable();
            $table->timestamps();
            
            $table->foreign('idprograma')->references('id')->on('programas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscripciones');
    }
}
