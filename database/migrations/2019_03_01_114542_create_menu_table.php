<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
            $table->increments('id');
			$table->integer('order');
			$table->string('icon', 25);
			$table->string('text', 25);
			$table->string('path', 50)->nullable();
			$table->integer('rol')->nullable();
			$table->integer('parent')->nullable();
			$table->boolean('model')->nullable();
			$table->boolean('active');
			$table->string('comments', 100)->nullable();
			$table->string('icon_alt', 25)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menu');
	}

}
