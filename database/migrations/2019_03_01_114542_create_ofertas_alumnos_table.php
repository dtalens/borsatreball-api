<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOfertasAlumnosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ofertas_alumnos', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_oferta')->unsigned()->index('id_oferta');
			$table->integer('id_alumno')->unsigned()->index('id_alumno');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ofertas_alumnos');
	}

}
