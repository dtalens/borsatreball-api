<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOfertasCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ofertas_ciclos', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_oferta')->unsigned()->index('id_oferta');
			$table->integer('id_ciclo')->unsigned()->index('id_ciclo');
			$table->integer('any_fin')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ofertas_ciclos');
	}

}
