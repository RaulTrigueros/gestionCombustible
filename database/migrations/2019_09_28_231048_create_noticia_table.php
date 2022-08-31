<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNoticiaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('noticias', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('titulo',100)->nullable();
                        $table->string('autor',100)->nullable();
			$table->date('fecha_noticia');
                        $table->string('cuerpo',10000)->nullable();
                        $table->boolean('estado')->default(1);
                        $table->binary('imagen')->nullable();
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
		Schema::drop('noticias');
	}

}
