<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmpresasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('empresas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('cif', 9)->nullable();
			$table->string('nombre', 25);
			$table->string('domicilio', 80)->nullable();
			$table->string('localidad', 25)->nullable();
			$table->string('contacto', 50)->nullable();
			$table->string('telefono', 25)->nullable();
			$table->string('web', 50)->nullable();
			$table->string('descripcion', 200)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('empresas');
	}

}
