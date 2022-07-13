<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResponsablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('responsables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre', 25);
			$table->string('apellidos', 50)->nullable();
			$table->timestamps();
			$table->foreign('id', 'responsable_foreign')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('responsables');
	}

}
