<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAlumnosCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('alumnos_ciclos', function(Blueprint $table)
		{
			$table->foreign('id_alumno', 'alumnos_foreign')->references('id')->on('alumnos')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('id_ciclo', 'ciclos_foreign')->references('id')->on('ciclos')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('alumnos_ciclos', function(Blueprint $table)
		{
			$table->dropForeign('alumnos_foreign');
			$table->dropForeign('ciclos_foreign');
		});
	}

}
