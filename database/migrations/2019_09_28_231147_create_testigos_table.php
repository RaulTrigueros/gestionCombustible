<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTestigosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('testigos', function(Blueprint $table)
		{
            $table->increments('id');
	        $table->integer('idpersonaregistro')->unsigned();
		    $table->integer('idpartida')->unsigned();
                     
            $table->foreign('idpersonaregistro')->references('id')->on('personaregistros');
            $table->foreign('idpartida')->references('id')->on('partidas');     
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('testigos');
	}

}
