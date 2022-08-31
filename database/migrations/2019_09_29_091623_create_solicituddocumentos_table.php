<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicituddocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicituddocumentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',120);
            $table->string('dui',25);
            $table->string('email',50)->nullable();
            $table->string('telefono',15);
            $table->string('informacion_solicitada',512);
            $table->string('formato',50);
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('solicituddocumentos');
    }
}
