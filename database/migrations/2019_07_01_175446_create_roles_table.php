<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion',30)->unique();
            $table->boolean('condicion')->default(1);
          
        });
        DB::table('roles')->insert(array('id'=>'1','descripcion'=>'Administrador'));
        DB::table('roles')->insert(array('id'=>'2','descripcion'=>'Combustible'));
        DB::table('roles')->insert(array('id'=>'3','descripcion'=>'Auxiliar'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
