<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCiudadano extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciudadano', function (Blueprint $table) {
            $table->Increments('id');
            
            $table->string('nombres',50);
            $table->string('apellidos',50);
            $table->string('edad',50);
            $table->string('oficio',50);
            $table->string('lugarorigen',10000);
            $table->string('dui',1000);



            
        });
        DB::table('ciudadano')->insert(array('id'=>'1','nombres'=>'Marlon Alain','apellidos'=>'Rivera Rivas','edad'=>'treinta años de edad','oficio'=>'agricultor en pequeño','lugarorigen'=>'San Juan Tepezontes','dui'=>'040315704'));
        DB::table('ciudadano')->insert(array('id'=>'2','nombres'=>'Edelmira Maricela','apellidos'=>'García López','edad'=>'veintinueve años de edad','oficio'=>'oficios domésticos','lugarorigen'=>'San Pedro Masahuat','dui'=>'044255736'));
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciudadano');
    }
}
