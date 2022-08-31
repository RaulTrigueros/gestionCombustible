<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nacimiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('nacimiento', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('paginas',50);
            $table->string('numeropartida',50);
            $table->integer('anio')->unsigned();
            $table->string('nombres',50);
            $table->string('sexo',20);
            $table->string('hora',200);
            $table->string('fecha',500);
            $table->string('lugarnacimiento',1000);
            $table->string('descripcion',10000)->nullable();
            $table->string('nombres_tutor',100);
            $table->string('apellidos_tutor',100);
            $table->string('manifiesto_tutor',1000);
            $table->string('expedido_tutor',1000);
            $table->string('dui_tutor',100);
            $table->string('fecha_expedicion',100)->nullable();
            $table->integer('idpadre')->unsigned();
            $table->integer('idmadre')->unsigned();
           

            $table->foreign('idpadre')->references('id')->on('ciudadano');
            $table->foreign('idmadre')->references('id')->on('ciudadano');
		});
        //

        DB::table('nacimiento')->insert(array('id'=>'1','paginas'=>'4 y 5','numeropartida'=>'5','anio'=>'2019','nombres'=>'MARLON ABIMAEL','sexo'=>'masculino','hora'=>'11:21','fecha'=>'02/02/2019','lugarnacimiento'=>'Hospital Nacional Santa Teresa de Zacatecoluca, La Paz','descripcion'=>'ambos  del domicilio de esta población y de nacionalidad salvadoreña','nombres_tutor'=>'Marlon Alain','apellidos_tutor'=>'Rivera Rivas','manifiesto_tutor'=>'ser el padre del recién nacido','expedido_tutor'=>'Zacatecoluca','dui_tutor'=>'040315704','fecha_expedicion'=>date('d/m/Y'),'idpadre'=>'1','idmadre'=>'2'));
		

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
            Schema::drop('nacimiento');
        
    
        //
    }
}
