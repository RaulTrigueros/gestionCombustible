<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefuncionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defuncion', function (Blueprint $table) {
            $table->increments('id');
             $table->string('paginas',50);
             $table->string('numeropartida',50);
             $table->integer('anio')->unsigned();
             $table->string('nombres',200);
             $table->string('sexo',20);
             $table->string('edad',100);
             $table->string('estadofam',20);
             $table->string('oficio',100);
             $table->string('lugarorigen',500);
             $table->string('domicilio',500);
             $table->string('nacionalidad',100);
             $table->string('nombrepadre',200);
             $table->string('nombremadre',400);
             $table->string('hora',100);
             $table->string('fechamuerte',200);
             $table->string('lugarmuerte',200);
             $table->string('causa',200);
             $table->string('duifallecido',100)->nullable();
             $table->string('nombreregistrador',200);
             $table->string('parentesco',50);
             $table->string('duiregistrador',100)->nullable();
             $table->string('nombretestigo',200);
             $table->string('duitestigo',100);
             $table->string('fechapartida',100);
             //$table->string('fechaexpe',100);
             
             
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
        Schema::dropIfExists('defuncion');
    }
}
