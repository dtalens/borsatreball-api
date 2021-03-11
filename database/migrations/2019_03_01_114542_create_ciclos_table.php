<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCiclosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ciclos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('codigo', 4);
			$table->string('ciclo', 50);
			$table->string('Dept', 3);
			$table->string('cDept', 50);
			$table->string('vDept', 50);
			$table->integer('responsable')->nullable();
			$table->string('vCiclo', 80)->nullable();
			$table->string('cCiclo', 80)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ciclos');
	}

}
