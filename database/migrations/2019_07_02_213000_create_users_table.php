<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Seeder;
use Illuminate\Database\Seeds\RoleSeeder;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned()->unique();
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
             
            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);
 
            $table->integer('role_id')->unsigned()->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->nullable();
 
            $table->rememberToken();
            //$table->timestamps();
        });
            DB::table('users')->insert(array('id'=>1,'usuario'=>'admin', 'password' => bcrypt('devspt2021'), 'condicion'=> 1, 'role_id'=>1));
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
