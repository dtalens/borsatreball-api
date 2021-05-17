<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOfertasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ofertas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('id_empresa')->unsigned()->nullable()->index('id_empresa');
			$table->string('descripcion', 200)->nullable();
			$table->string('puesto', 50)->nullable();
			$table->string('tipo_contrato', 50)->nullable();
			$table->boolean('activa')->nullable();
			$table->string('contacto', 50)->nullable();
			$table->string('telefono', 25)->nullable();
			$table->string('email', 50)->nullable();
			$table->boolean('mostrar_contacto')->default(true);
			$table->boolean('validada')->default(false);
			$table->boolean('estudiando')->nullable();
			$table->boolean('archivada')->default(false);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ofertas');
	}

}
