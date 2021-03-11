<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlumnosCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alumnos_ciclos', function(Blueprint $table)
		{
            $table->increments('id');
			$table->integer('id_alumno')->unsigned()->index('id_alumno');
			$table->integer('id_ciclo')->unsigned()->index('id_ciclo');
			$table->integer('any')->nullable();
			$table->boolean('validado')->default(false);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('alumnos_ciclos');
	}

}
