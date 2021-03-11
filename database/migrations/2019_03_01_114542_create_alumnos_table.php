<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlumnosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alumnos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre', 25);
			$table->string('apellidos', 50)->nullable();
			$table->string('domicilio', 80)->nullable();
			$table->string('telefono', 25)->nullable();
			$table->boolean('info')->default(false);
			$table->boolean('bolsa')->default(false);
			$table->string('cv_enlace', 80)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('alumnos');
	}

}
