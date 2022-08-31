<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateServicioTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('servicio', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre',50);
			$table->string('descripcion',10000)->nullable();
			$table->boolean('estado')->default(1);
            $table->timestamps();
		});
		DB::table('servicio')->insert(array('id'=>'1','nombre'=>'Registro familiar','descripcion'=>'Asentamientos de nacimientos, matrimonio, defunciones entre otros.
		Precios:
-Partida de nacimiento:  $3.00 
-matrimonios: $16.75 
-Partida de matrimonio: $4.00 
-Partida de defunción:  $3.50 
-Reposición de partidas: $5.00','estado'=>1));
		DB::table('servicio')->insert(array('id'=>'2','nombre'=>'Portal de transparencia','descripcion'=>'Unidad de acceso a la informacion publica','estado'=>1));
		DB::table('servicio')->insert(array('id'=>'3','nombre'=>'Solicitud de transparencia','descripcion'=>'Solicitar la informacion publica','estado'=>1));


	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('servicio');
	}

}
