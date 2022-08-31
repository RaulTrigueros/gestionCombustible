<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idEncargado')->unsigned();
            $table->string('nombre', 128);
            $table->string('descripcion',256)->nullable();
            $table->decimal('montoEstimado',12,2);
            $table->decimal('montoReal', 12,2)->nullable(); 

            $table->foreign('idEncargado')->references('id')->on('encargado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyecto');
    }
}
