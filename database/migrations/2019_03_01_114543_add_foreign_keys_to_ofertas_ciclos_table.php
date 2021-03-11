<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOfertasCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ofertas_ciclos', function(Blueprint $table)
		{
			$table->foreign('id_ciclo', 'ciclo_oferta_ciclo_foreign')->references('id')->on('ciclos')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_oferta', 'oferta_oferta_ciclo_foreign')->references('id')->on('ofertas')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ofertas_ciclos', function(Blueprint $table)
		{
			$table->dropForeign('ciclo_oferta_ciclo_foreign');
			$table->dropForeign('oferta_oferta_ciclo_foreign');
		});
	}

}
