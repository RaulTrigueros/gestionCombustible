<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudpartidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudpartidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idtipodocumento')->unsigned();
            $table->string('nombre',120);
            $table->string('dui',25);
            $table->string('email',50)->nullable();
            $table->string('telefono',15);
            $table->string('anombrede',120);
            $table->date('fecha_realizado');
            $table->boolean('estado')->default(1);
            $table->timestamps();
            
            $table->foreign('idtipodocumento')->references('id')->on('tipodocumentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudpartidas');
    }
}
