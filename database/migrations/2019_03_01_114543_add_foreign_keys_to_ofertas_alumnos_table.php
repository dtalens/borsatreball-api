<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOfertasAlumnosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ofertas_alumnos', function(Blueprint $table)
		{
			$table->foreign('id_oferta', 'oferta_alumno_foreign')->references('id')->on('ofertas')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('id_alumno', 'alumno_oferta_foreign')->references('id')->on('alumnos')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ofertas_alumnos', function(Blueprint $table)
		{
			$table->dropForeign('oferta_alumno_foreign');
			$table->dropForeign('alumno_oferta_foreign');
		});
	}

}
