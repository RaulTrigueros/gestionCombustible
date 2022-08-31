<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentotransparenciaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documentotransparencia', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('iduser')->unsigned();
			$table->string('nombre');
			$table->string('nombrearchivo');
			$table->string('descripcion');
			//$table->binary('archivo')->nullable();
			$table->date('fecha');
			$table->timestamps();

			$table->foreign('iduser')->references('id')->on('users');
		});
		DB::statement("ALTER TABLE documentotransparencia ADD archivo MEDIUMBLOB");
		// DB::statement("ALTER TABLE documentotransparencia ADD archivo BYTEA");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('documentotransparencia');
	}

}
