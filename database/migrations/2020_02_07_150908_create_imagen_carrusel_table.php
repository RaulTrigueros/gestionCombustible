<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagenCarruselTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagen_carrusel', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_carrusel')->unsigned();
            $table->string('titulo');
            $table->string('descripcion');
            $table->integer('orden');
            $table->timestamps();
            $table->string('imagen');
            
            $table->foreign('id_carrusel')->references('id')->on('carrusel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagen_carrusel');
    }
}
